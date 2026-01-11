<?php
header('Content-Type: application/json; charset=utf-8');

class CompanyController
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function index()
    {
        $companyModel = new Company($this->pdo);
        $companies = $companyModel->allWithJobs();
        echo json_encode($companies);
    }
}
