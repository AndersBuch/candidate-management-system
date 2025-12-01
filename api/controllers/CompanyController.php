<?php

class CompanyController
{
    /** @var PDO */
    private $pdo;

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
