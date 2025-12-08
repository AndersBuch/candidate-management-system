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
                a.id       AS application_id,
                a.status   AS application_status,

                c.id       AS candidate_id,
                c.first_name,
                c.last_name,
                c.email,
                c.phone_number,
                c.address,
                c.zip_code,
                c.city,
                c.gender,
                c.age,
                c.linkedin_url,
                c.current_position,
                c.note,
                c.created_at AS candidate_created_at
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
                // IDs
                'id'            => (int) $row['candidate_id'],
                'applicationId' => (int) $row['application_id'],

                // Navne
                'first_name'    => $row['first_name'],
                'last_name'     => $row['last_name'],
                'name'          => trim($row['first_name'] . ' ' . $row['last_name']),

                // Kontakt
                'email'         => $row['email'],
                'phone'         => $row['phone_number'],

                // Adresse
                'address'       => $row['address'],
                'zip_code'      => $row['zip_code'],
                'city'          => $row['city'],

                // Persondata
                'gender'        => $row['gender'],   // 'Mand', 'Kvinde', 'Andet'
                'age'           => $row['age'],

                // Job / profil
                'linkedin'          => $row['linkedin_url'],
                'current_position'  => $row['current_position'],
                'note'              => $row['note'],

                // Status fra Application (dansk)
                'status'        => $row['application_status'],

                // Tidsstempler
                'created_at'    => $row['candidate_created_at'],
            ];
        }

        return $candidates;
    }
}