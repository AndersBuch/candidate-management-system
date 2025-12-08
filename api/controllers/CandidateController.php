<?php

class CandidateController {
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }
    
public function index() {
    header('Content-Type: application/json; charset=utf-8');
    try {
        $stmt = $this->pdo->query("SELECT id, first_name, last_name, phone_number, email, status, linkedin_url FROM candidate");
        $candidates = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($candidates);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode([
            "error" => "Could not fetch candidates",
            "message" => $e->getMessage()
        ]);
    }
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
            $candidateModel = new Candidate($this->pdo);
            $id = $candidateModel->create($data);

            http_response_code(201);
            echo json_encode([
                "id" => $id,
                "status" => $data["status"] ?? "Contact"
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                "error" => "Could not create candidate",
                "message" => $e->getMessage()
            ]);
        }
    }

}
