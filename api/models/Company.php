<?php

class Company
{
    public function __construct(private PDO $pdo)
    {
    }

    public function allWithJobs(): array
    {
        $sql = "
            SELECT 
                c.id   AS company_id,
                c.name AS company_name,
                j.id   AS job_id,
                j.title,
                j.public_id
            FROM Company c
            LEFT JOIN Job j ON j.company_id = c.id
            ORDER BY c.name, j.title
        ";

        $stmt = $this->pdo->query($sql);
        $rows = $stmt->fetchAll();

        $companies = [];

        foreach ($rows as $row) {
            $companyId = (int) $row['company_id'];

            if (!isset($companies[$companyId])) {
                $companies[$companyId] = [
                    'id'        => $companyId,
                    'name'      => $row['company_name'],
                    'positions' => [],
                ];
            }

            if (!empty($row['job_id'])) {
                $companies[$companyId]['positions'][] = [
                    'id'            => (int) $row['job_id'],
                    'name'          => $row['title'],
                    'applicationId' => $row['public_id'], // public_id i DB
                ];
            }
        }

        return array_values($companies);
    }
}