<?php

class Candidate {
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

public function create($data) {

    $sql = "INSERT INTO candidate (
        first_name, last_name, email, phone_number, address, zip_code,
        city, gender, age, linkedin_url, current_position, note, status
    ) VALUES (
        :first_name, :last_name, :email, :phone_number, :address, :zip_code,
        :city, :gender, :age, :linkedin_url, :current_position, :note, :status
    )";

    $stmt = $this->pdo->prepare($sql);

    $stmt->execute([
        ":first_name" => $data["first_name"] ?? "",
        ":last_name" => $data["last_name"] ?? "",
        ":email" => $data["email"] ?? "",
        ":phone_number" => $data["phone_number"] ?? "",
        ":address" => $data["address"] ?? "",
        ":zip_code" => $data["zip_code"] ?? "",
        ":city" => $data["city"] ?? "",
        ":gender" => $data["gender"] ?? "",
        ":age" => $data["age"] ?? "",
        ":linkedin_url" => $data["linkedin_url"] ?? "",
        ":current_position" => $data["current_position"] ?? "",
        ":note" => $data["note"] ?? "",
        ":status" => $data["status"] ?? "Contact"
    ]);

    return $this->pdo->lastInsertId();
}

}
