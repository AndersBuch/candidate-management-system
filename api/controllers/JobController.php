<?php

class JobController
{
    /** @var PDO */
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function candidates(int $jobId)
    {
        if ($jobId <= 0) {
            http_response_code(400);
            echo json_encode(['error' => 'Ugyldigt job-id']);
            return;
        }

        $candidateModel = new Candidate($this->pdo);
        $candidates = $candidateModel->byJob($jobId);

        echo json_encode($candidates);
    }
}
