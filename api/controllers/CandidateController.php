<?php

class CandidateController {
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }
    
public function index() {
    header('Content-Type: application/json; charset=utf-8');

    $jobId = $_GET['jobId'] ?? null;

    // Start med join til application, hvis der filtreres på job
    if ($jobId) {
        $sql = "SELECT c.*
                FROM candidate c
                INNER JOIN application a ON a.candidate_id = c.id
                WHERE a.job_id = ?";
        $params = [$jobId];
    } else {
        // Ufiltreret liste
        $sql = "SELECT * FROM candidate";
        $params = [];
    }

    $stmt = $this->pdo->prepare($sql);
    $stmt->execute($params);

    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
}



    // POST /candidates
public function store() {
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

        // Hvis der er sendt job_id med, opret en application
        if (!empty($data['job_id'])) {
            $stmt = $this->pdo->prepare("
                INSERT INTO application (candidate_id, job_id, status)
                VALUES (:candidate_id, :job_id, :status)
            ");

            $stmt->execute([
                ':candidate_id' => $candidateId,
                ':job_id'       => $data['job_id'],
                ':status'       => $data['status'] ?? 'Afventer'
            ]);
        }

        $this->pdo->commit();

        http_response_code(201);
        echo json_encode([
            "id"     => $candidateId,
            "status" => $data["status"] ?? "Contact"
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

 public function updateStatus($id) {
    header('Content-Type: application/json; charset=utf-8');

    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['status'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Status is required']);
        return;
    }

    try {
        $sql = "UPDATE candidate SET status = :status WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':status' => $data['status'],
            ':id' => $id
        ]);

        echo json_encode([
            'success' => true,
            'id' => $id,
            'status' => $data['status']
        ]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Could not update status', 'message' => $e->getMessage()]);
    }
}

    // Hent antal kandidater
    public function count() {
        $stmt = $this->pdo->query("SELECT COUNT(*) as total FROM candidate");
        $result = $stmt->fetch();
        echo json_encode(['count' => (int)$result['total']]);
    }

    // Hent antal kandidater oprettet i sidste måned
public function countRecent($days = 30) {
    $stmt = $this->pdo->prepare("
        SELECT COUNT(*) as total 
        FROM candidate 
        WHERE created_at >= DATE_SUB(NOW(), INTERVAL :days DAY)
    ");
    $stmt->execute([':days' => $days]);
    $result = $stmt->fetch();
    echo json_encode(['count' => (int)$result['total']]);
}



}
