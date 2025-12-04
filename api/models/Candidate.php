<?php

class Candidate
{
    /** @var PDO */
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Hent alle kandidater til et bestemt job (job_id).
     */
    public function byJob(int $jobId): array
    {
        $sql = "
            SELECT 
                a.id AS application_id,
                a.status,
                c.id AS candidate_id,
                CONCAT(c.first_name, ' ', c.last_name) AS name,
                c.email,
                c.phone_number,
                c.linkedin_url
            FROM Application a
            INNER JOIN candidate c ON c.id = a.candidate_id
            WHERE a.job_id = :job_id
            ORDER BY a.created_at DESC
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['job_id' => $jobId]);
        $rows = $stmt->fetchAll();

        $candidates = [];

        foreach ($rows as $row) {
            $candidates[] = [
                'id'            => (int) $row['candidate_id'],
                'applicationId' => (int) $row['application_id'],
                'name'          => $row['name'],
                'email'         => $row['email'],
                'phone'         => $row['phone_number'],
                'status'        => $row['status'],        // fx 'Afventer', 'Accepteret' osv.
                'linkedin'      => $row['linkedin_url'],  // kan være null
            ];
        }

        return $candidates;
    }
}
