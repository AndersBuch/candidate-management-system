<?php

class CandidateController {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
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

        $data = json_decode(file_get_contents("php://input"), true);
        file_put_contents("debug.log", "Received data:\n" . print_r($data, true), FILE_APPEND);

        if (!$data) {
            http_response_code(400);
            echo json_encode(["error" => "Invalid JSON"]);
            return;
        }

        try {
            $this->pdo->beginTransaction();

            $candidateModel = new Candidate($this->pdo);
            $candidateId = $candidateModel->create($data);

            // Hvis der er sendt job_id med, opret en application-row
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
            }

            $this->pdo->commit();

            http_response_code(201);
            echo json_encode([
                "id"     => $candidateId,
                "status" => $data["status"] ?? "Afventer"
            ]);
        } catch (Exception $e) {
            $this->pdo->rollBack();
            http_response_code(500);
            echo json_encode([
                "error"   => "Could not create candidate",
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

    // PATCH /api/candidates/{id}
public function update($id) {
    header('Content-Type: application/json; charset=utf-8');

    $data = json_decode(file_get_contents("php://input"), true);

    $sql = "
        UPDATE candidate SET
            first_name = :first_name,
            last_name = :last_name,
            email = :email,
            phone_number = :phone,
            address = :address,
            zip_code = :zip,
            city = :city,
            age = :age,
            linkedin_url = :linkedin,
            current_position = :position,
            note = :note
        WHERE id = :id
    ";

    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([
        ':first_name' => $data['first_name'],
        ':last_name'  => $data['last_name'],
        ':email'      => $data['email'],
        ':phone'      => $data['phone'],
        ':address'    => $data['address'],
        ':zip'        => $data['zip_code'],
        ':city'       => $data['city'],
        ':age'        => $data['age'],
        ':linkedin'   => $data['linkedin'],
        ':position'   => $data['current_position'],
        ':note'       => $data['note'],
        ':id'         => $id
    ]);

    echo json_encode(['success' => true]);
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

        $stmt = $this->pdo->prepare("
            SELECT COUNT(*) as total 
            FROM candidate 
            WHERE created_at >= DATE_SUB(NOW(), INTERVAL :days DAY)
        ");
        $stmt->execute([':days' => $days]);
        $result = $stmt->fetch();
        echo json_encode(['count' => (int)$result['total']]);
    }

    // DELETE /api/candidates/{id}
public function destroy($id) {
    header('Content-Type: application/json; charset=utf-8');

    try {
        // Start transaction — fordi application og documents også skal slettes
        $this->pdo->beginTransaction();

        // Slet kandidat → pga. CASCADE slettes application + documents automatisk
        $stmt = $this->pdo->prepare("DELETE FROM candidate WHERE id = ?");
        $stmt->execute([$id]);

        $this->pdo->commit();

        echo json_encode(["success" => true, "deleted_id" => $id]);

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
