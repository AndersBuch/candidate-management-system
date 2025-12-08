<?php

class CandidateController {
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }
    
public function index() {
    header('Content-Type: application/json; charset=utf-8');

    $position = $_GET['positionId'] ?? null; // vi sender tekst som positionId

    $sql = "SELECT * FROM candidate";
    $params = [];

    if ($position) {
        $sql .= " WHERE current_position = ?";
        $params[] = $position; // fx "Maskinmester"
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
