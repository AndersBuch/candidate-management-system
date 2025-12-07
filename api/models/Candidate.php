<?php

class Candidate {
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function create($data) {
        $sql = "INSERT INTO candidate 
            (first_name, last_name, email, phone_number, address, zip_code, city, gender, age, linkedin_url, current_position, note)
        VALUES 
            (:first_name, :last_name, :email, :phone_number, :address, :zip_code, :city, :gender, :age, :linkedin_url, :current_position, :note)";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($data);

        return $this->pdo->lastInsertId();
    }
}
