<?php

class Document {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function listByApplication(int $applicationId): array {
        $stmt = $this->pdo->prepare("
            SELECT id, application_id, kind, file_name, file_url, uploaded_at
            FROM document
            WHERE application_id = :id
            ORDER BY uploaded_at DESC
        ");
        $stmt->execute([':id' => $applicationId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById(int $id): ?array {
        $stmt = $this->pdo->prepare("SELECT * FROM document WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public function getByApplicationAndKind(int $applicationId, string $kind): ?array {
        $stmt = $this->pdo->prepare("
            SELECT * FROM document
            WHERE application_id = :aid AND kind = :kind
            LIMIT 1
        ");
        $stmt->execute([':aid' => $applicationId, ':kind' => $kind]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public function insert(int $applicationId, string $kind, string $fileName, string $fileUrl): int {
        $stmt = $this->pdo->prepare("
            INSERT INTO document (application_id, kind, file_name, file_url)
            VALUES (:aid, :kind, :name, :url)
        ");
        $stmt->execute([
            ':aid' => $applicationId,
            ':kind' => $kind,
            ':name' => $fileName,
            ':url' => $fileUrl,
        ]);
        return (int)$this->pdo->lastInsertId();
    }

    public function update(int $id, string $fileName, string $fileUrl): void {
        $stmt = $this->pdo->prepare("
            UPDATE document SET file_name = :name, file_url = :url
            WHERE id = :id
        ");
        $stmt->execute([
            ':name' => $fileName,
            ':url' => $fileUrl,
            ':id' => $id,
        ]);
    }

    public function deleteById(int $id): void {
        $stmt = $this->pdo->prepare("DELETE FROM document WHERE id = :id");
        $stmt->execute([':id' => $id]);
    }
}
