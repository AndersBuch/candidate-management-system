<?php

class CompanyController
{
    public function __construct(private PDO $pdo)
    {
    }

    public function index(): void
    {
        $companyModel = new Company($this->pdo);
        $companies = $companyModel->allWithJobs();
        echo json_encode($companies);
    }
}