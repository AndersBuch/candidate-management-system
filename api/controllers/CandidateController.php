<?php

class CandidateController {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    private function requestData(): array {
    $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
    if (stripos($contentType, 'multipart/form-data') !== false) {
        return $_POST; // FormData felter
    }
    $data = json_decode(file_get_contents("php://input"), true);
    return is_array($data) ? $data : [];
}

private function uploadsBasePath(): string {
    $base = realpath(__DIR__ . '/../../storage/uploads');
    if (!$base) throw new Exception("Missing storage/uploads");
    return $base;
}

private function normalizeFilesArray(array $fileField): array {
    if (!is_array($fileField['name'])) return [$fileField];

    $out = [];
    $count = count($fileField['name']);
    for ($i = 0; $i < $count; $i++) {
        $out[] = [
            'name'     => $fileField['name'][$i],
            'type'     => $fileField['type'][$i] ?? null,
            'tmp_name' => $fileField['tmp_name'][$i],
            'error'    => $fileField['error'][$i],
            'size'     => $fileField['size'][$i] ?? null,
        ];
    }
    return $out;
}

private function saveUploadedFile(int $applicationId, array $file): array {
    $base = $this->uploadsBasePath();
    $dir = $base . "/applications/{$applicationId}";
    if (!is_dir($dir)) mkdir($dir, 0755, true);

    $original = $file['name'] ?? 'file';
    $ext = strtolower(pathinfo($original, PATHINFO_EXTENSION));
    $safe = bin2hex(random_bytes(16)) . ($ext ? ".{$ext}" : "");

    $relative = "applications/{$applicationId}/{$safe}";
    $target = $base . "/" . $relative;

    if (!move_uploaded_file($file['tmp_name'], $target)) {
        throw new Exception("Could not save file");
    }

    return ['original' => $original, 'relative' => $relative];
}

private function deleteRelativeFile(string $relativePath): void {
    $base = $this->uploadsBasePath();
    $full = $base . "/" . ltrim($relativePath, "/");

    $real = realpath($full);
    $realBase = realpath($base);

    if ($real && $realBase && str_starts_with($real, $realBase) && is_file($real)) {
        @unlink($real);
    }
}

private function handleDocumentUploads(int $applicationId): void {
    if (empty($_FILES)) return;

    require_once __DIR__ . '/../models/Document.php';
    $docModel = new Document($this->pdo);

    // single filer (erstatter hvis findes)
    $singleMap = [
        'cv'        => 'CV',
        'ansogning' => 'Ansøgning',
        'photo'     => 'Foto',
    ];

    foreach ($singleMap as $field => $kind) {
        if (!isset($_FILES[$field])) continue;
        $f = $_FILES[$field];
        if (($f['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) continue;

        $saved = $this->saveUploadedFile($applicationId, $f);

        $existing = $docModel->getByApplicationAndKind($applicationId, $kind);
        if ($existing) {
            $this->deleteRelativeFile($existing['file_url']);
            $docModel->update((int)$existing['id'], $saved['original'], $saved['relative']);
        } else {
            $docModel->insert($applicationId, $kind, $saved['original'], $saved['relative']);
        }
    }

    // multiple "andet[]"
    if (isset($_FILES['andet'])) {
        foreach ($this->normalizeFilesArray($_FILES['andet']) as $f) {
            if (($f['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) continue;
            $saved = $this->saveUploadedFile($applicationId, $f);
            $docModel->insert($applicationId, 'Andet', $saved['original'], $saved['relative']);
        }
    }
}

    // OPTIONAL: generel liste, bruges ikke til job-filteret i øjeblikket
    public function index() {
        header('Content-Type: application/json; charset=utf-8');

        $jobId = $_GET['jobId'] ?? null;

        if ($jobId) {
            $sql = "SELECT c.*
                    FROM candidate c
                    INNER JOIN application a ON a.candidate_id = c.id
                    WHERE a.job_id = ?";
            $params = [$jobId];
        } else {
            $sql = "SELECT * FROM candidate";
            $params = [];
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    // POST /api/candidates
public function store() {
    header('Content-Type: application/json; charset=utf-8');

    $data = $this->requestData();

    if (!$data) {
        http_response_code(400);
        echo json_encode(["error" => "No data received"]);
        return;
    }

    try {
        $this->pdo->beginTransaction();

        $candidateModel = new Candidate($this->pdo);
        $candidateId = $candidateModel->create($data);

        $applicationId = null;

        if (!empty($data['job_id'])) {
            $stmt = $this->pdo->prepare("
                INSERT INTO application (candidate_id, job_id, status)
                VALUES (:candidate_id, :job_id, :status)
            ");
            $stmt->execute([
                ':candidate_id' => $candidateId,
                ':job_id'       => $data['job_id'],
                ':status'       => $data['status'] ?? 'Afventer',
            ]);

            $applicationId = (int)$this->pdo->lastInsertId();
        }

        // docs upload (kun hvis application findes)
        if ($applicationId) {
            $this->handleDocumentUploads($applicationId);
        }

        $this->pdo->commit();

        http_response_code(201);
        echo json_encode([
            "id" => $candidateId,
            "application_id" => $applicationId,
            "status" => $data["status"] ?? "Afventer"
        ]);
    } catch (PDOException $e) {
        $this->pdo->rollBack();

        $msg = $e->getMessage();

        // Duplicate email (UNIQUE constraint)
        if (
            $e->getCode() === '23000' &&
            stripos($msg, 'duplicate') !== false &&
            stripos($msg, 'email') !== false
        ) {
            http_response_code(409); // 👈 IKKE 500
            echo json_encode([
                "error" => "DUPLICATE_EMAIL",
                "field" => "email",
                "message" => "Der findes allerede en bruger med denne email."
            ]);
            return;
        }

        // Andre databasefejl
        http_response_code(500);
        echo json_encode([
            "error" => "Database error",
            "message" => $msg
        ]);

    } catch (Exception $e) {
        $this->pdo->rollBack();
        http_response_code(500);
        echo json_encode([
            "error" => "Could not create candidate",
            "message" => $e->getMessage()
        ]);
    }
}


    // PATCH /api/candidates/{id}/status
    // Opdaterer status på en application (id = application_id)
    public function updateStatus($id) {
        header('Content-Type: application/json; charset=utf-8');

        $data = json_decode(file_get_contents("php://input"), true);

        if (!isset($data['status'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Status is required']);
            return;
        }

        try {
            $sql = "UPDATE application SET status = :status WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':status' => $data['status'],
                ':id'     => $id
            ]);

            http_response_code(200);
            echo json_encode(['success' => true, 'status' => $data['status']]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Could not update status', 'message' => $e->getMessage()]);
        }
    }

    public function update($id) {
        header('Content-Type: application/json; charset=utf-8');

        $data = $this->requestData();
        if (!$data) {
            http_response_code(400);
            echo json_encode(["error" => "No data received"]);
            return;
        }

    // Vi skal kende application_id for at uploade docs til rigtig mappe
    $applicationId = isset($data['application_id']) ? (int)$data['application_id'] : null;

    try {
        $this->pdo->beginTransaction();

        $sql = "
            UPDATE candidate SET
                first_name = :first_name,
                last_name = :last_name,
                email = :email,
                phone_number = :phone_number,
                address = :address,
                zip_code = :zip_code,
                city = :city,
                gender = :gender,
                age = :age,
                linkedin_url = :linkedin_url,
                current_position = :position,
                note = :note
            WHERE id = :id
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':first_name'   => $data['first_name'] ?? null,
            ':last_name'    => $data['last_name'] ?? null,
            ':email'        => $data['email'] ?? null,

            // understøt både "phone" (EditModal) og "phone_number" (DB)
            ':phone_number' => $data['phone_number'] ?? ($data['phone'] ?? null),

            ':address'      => $data['address'] ?? null,
            ':zip_code'     => $data['zip_code'] ?? ($data['postal'] ?? null),
            ':city'         => $data['city'] ?? null,
            ':gender'       => $data['gender'] ?? null,
            ':age'          => $data['age'] ?? null,

            // understøt både linkedin / linkedin_url
            ':linkedin_url' => $data['linkedin_url'] ?? ($data['linkedin'] ?? null),

            ':position'     => $data['current_position'] ?? null,
            ':note'         => $data['note'] ?? ($data['message'] ?? null),
            ':id'           => (int)$id
        ]);

        if ($applicationId) {
            $this->handleDocumentUploads($applicationId);
        }

        $this->pdo->commit();
        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        $this->pdo->rollBack();
        http_response_code(500);
        echo json_encode(['error' => 'Could not update candidate', 'message' => $e->getMessage()]);
    }
}



    // Hent antal kandidater (alle)
    public function count() {
        header('Content-Type: application/json; charset=utf-8');

        $stmt = $this->pdo->query("SELECT COUNT(*) as total FROM candidate");
        $result = $stmt->fetch();
        echo json_encode(['count' => (int)$result['total']]);
    }

// Hent antal kandidater oprettet i de sidste X dage
public function countRecent($days = 30) {
    header('Content-Type: application/json; charset=utf-8');

    $days = (int)$days;
    if ($days < 0) $days = 0;

    try {
        $sql = "
            SELECT COUNT(*) as total
            FROM `candidate`
            WHERE created_at >= DATE_SUB(NOW(), INTERVAL $days DAY)
        ";

        $stmt = $this->pdo->query($sql);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        echo json_encode(['count' => (int)($result['total'] ?? 0)]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode([
            'error' => 'Could not count recent candidates',
            'message' => $e->getMessage()
        ]);
    }
}

    // DELETE /api/candidates/{id}
public function destroy($id) {
    header('Content-Type: application/json; charset=utf-8');

    try {
        $this->pdo->beginTransaction();

        // Find application IDs for candidate (så vi kan fjerne mapperne bagefter)
        $stmt = $this->pdo->prepare("SELECT id FROM application WHERE candidate_id = ?");
        $stmt->execute([(int)$id]);
        $appIds = $stmt->fetchAll(PDO::FETCH_COLUMN);

        // Find alle filer der hører til candidate via application -> document
        $stmt = $this->pdo->prepare("
            SELECT d.file_url
            FROM document d
            INNER JOIN application a ON a.id = d.application_id
            WHERE a.candidate_id = ?
        ");
        $stmt->execute([(int)$id]);
        $files = $stmt->fetchAll(PDO::FETCH_COLUMN);

        foreach ($files as $rel) {
            if ($rel) $this->deleteRelativeFile($rel);
        }

        // Fjern tomme application-mapper (EFTER filer er slettet)
        $base = $this->uploadsBasePath(); // /storage/uploads
        foreach ($appIds as $appId) {
            $dir = $base . "/applications/" . (int)$appId;

            if (is_dir($dir)) {
                $items = array_diff(scandir($dir), ['.', '..']);
                if (count($items) === 0) {
                    @rmdir($dir);
                }
            }
        }

        // Slet candidate (cascade sletter application + document rows hvis FK er sat)
        $stmt = $this->pdo->prepare("DELETE FROM candidate WHERE id = ?");
        $stmt->execute([(int)$id]);

        $this->pdo->commit();
        echo json_encode(["success" => true, "deleted_id" => (int)$id]);

    } catch (Exception $e) {
        $this->pdo->rollBack();
        http_response_code(500);
        echo json_encode([
            "error" => "Could not delete candidate",
            "message" => $e->getMessage()
        ]);
    }
}



}
