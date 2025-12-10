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
    // NB: her tolker vi {id} som APPLICATION-ID (ikke kandidat-id)
    public function updateStatus($id) {
        header('Content-Type: application/json; charset=utf-8');

        $data = json_decode(file_get_contents('php://input'), true);

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

            echo json_encode([
                'success' => true,
                'id'      => $id,
                'status'  => $data['status']
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'error'   => 'Could not update status',
                'message' => $e->getMessage()
            ]);
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
