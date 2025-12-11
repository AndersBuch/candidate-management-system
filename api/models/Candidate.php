<?php

class Candidate {
    /** @var PDO */
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Opretter en kandidat (uden status – status ligger i application-tabellen).
     */
    public function create(array $data): int
    {
        $sql = "INSERT INTO candidate (
            first_name, last_name, email, phone_number, address, zip_code,
            city, gender, age, linkedin_url, current_position, note
        ) VALUES (
            :first_name, :last_name, :email, :phone_number, :address, :zip_code,
            :city, :gender, :age, :linkedin_url, :current_position, :note
        )";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            ':first_name'       => $data['first_name']       ?? '',
            ':last_name'        => $data['last_name']        ?? '',
            ':email'            => $data['email']            ?? '',
            ':phone_number'     => $data['phone_number']     ?? '',
            ':address'          => $data['address']          ?? '',
            ':zip_code'         => $data['zip_code']         ?? '',
            ':city'             => $data['city']             ?? '',
            ':gender'           => $data['gender']           ?? 'Andet',
            ':age'              => $data['age']              ?? null,
            ':linkedin_url'     => $data['linkedin_url']     ?? null,
            ':current_position' => $data['current_position'] ?? null,
            ':note'             => $data['note']             ?? null,
        ]);

        return (int) $this->pdo->lastInsertId();
    }

    /**
     * Hent alle kandidater til et bestemt job (job_id).
     * Status kommer fra application.status.
     */
    public function byJob(int $jobId): array
    {
        $sql = "
            SELECT 
                a.id         AS application_id,
                a.status     AS application_status,

                c.id         AS candidate_id,
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
            FROM application a
            INNER JOIN candidate c ON c.id = a.candidate_id
            WHERE a.job_id = :job_id
            ORDER BY a.created_at DESC
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['job_id' => $jobId]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $candidates = [];

        foreach ($rows as $row) {
            $candidates[] = [
                // ids
                'id'             => (int) $row['candidate_id'],       // kandidat-id
                'applicationId'  => (int) $row['application_id'],     // application-id (bruges til status-opdatering)

                // navn
                'first_name'     => $row['first_name'],
                'last_name'      => $row['last_name'],
                'name'           => trim($row['first_name'] . ' ' . $row['last_name']),

                // kontakt
                'email'          => $row['email'],
                'phone'          => $row['phone_number'],

                // adresse
                'address'        => $row['address'],
                'zip_code'       => $row['zip_code'],
                'city'           => $row['city'],

                // persondata
                'gender'         => $row['gender'],
                'age'            => $row['age'],

                // profil
                'linkedin'       => $row['linkedin_url'],
                'current_position' => $row['current_position'],
                'note'           => $row['note'],

                // status (PR. JOB) – det er den frontend skal bruge
                'status'         => $row['application_status'],

                'created_at'     => $row['candidate_created_at'],
            ];
        }

        return $candidates;
    }
}
