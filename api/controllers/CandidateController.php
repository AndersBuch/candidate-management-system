<?php

class CandidateController {
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function store() {
        $data = json_decode(file_get_contents("php://input"), true);

        if (!$data) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid JSON']);
            return;
        }

        $model = new Candidate($this->pdo);
        $id = $model->create($data);

        echo json_encode([
            'id' => $id,
            'message' => 'Candidate created'
        ]);
    }

    public function index() {
    $stmt = $this->pdo->query("SELECT * FROM candidate ORDER BY created_at DESC");
    $candidates = $stmt->fetchAll();
    echo json_encode($candidates);
}

}
