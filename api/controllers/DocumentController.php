<?php

require_once __DIR__ . '/../models/Document.php';

class DocumentController {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    private function uploadsBasePath(): string {
        $base = realpath(__DIR__ . '/../../storage/uploads');
        if (!$base) throw new Exception("Missing storage/uploads");
        return $base;
    }

    private function deleteRelativeFile(string $relativePath): void {
        $base = $this->uploadsBasePath();
        $full = $base . "/" . ltrim($relativePath, "/");

        $real = realpath($full);
        $realBase = realpath($base);

        if ($real && $realBase && str_starts_with($real, $realBase) && is_file($real)) {
            @unlink($real);
        }
    }

    // GET /api/applications/{id}/documents
    public function listByApplication($applicationId) {
        header('Content-Type: application/json; charset=utf-8');
        $model = new Document($this->pdo);
        echo json_encode($model->listByApplication((int)$applicationId));
    }

    // DELETE /api/documents/{id}
    public function delete($id) {
        header('Content-Type: application/json; charset=utf-8');

        try {
            $this->pdo->beginTransaction();

            $model = new Document($this->pdo);
            $doc = $model->getById((int)$id);

            if (!$doc) {
                $this->pdo->rollBack();
                http_response_code(404);
                echo json_encode(['error' => 'Document not found']);
                return;
            }

            if (!empty($doc['file_url'])) {
                $this->deleteRelativeFile($doc['file_url']);
            }

            $model->deleteById((int)$id);

            $this->pdo->commit();
            echo json_encode(['success' => true]);
        } catch (Exception $e) {
            $this->pdo->rollBack();
            http_response_code(500);
            echo json_encode(['error' => 'Could not delete document', 'message' => $e->getMessage()]);
        }
    }

    // GET /api/documents/{id}/download
    public function download($id) {
        try {
            $model = new Document($this->pdo);
            $doc = $model->getById((int)$id);

            if (!$doc) {
                http_response_code(404);
                echo "Not found";
                return;
            }

            $base = $this->uploadsBasePath();
            $full = $base . "/" . ltrim($doc['file_url'], "/");

            $real = realpath($full);
            $realBase = realpath($base);

            if (!$real || !$realBase || !str_starts_with($real, $realBase) || !is_file($real)) {
                http_response_code(404);
                echo "File missing";
                return;
            }

            $filename = $doc['file_name'] ?: basename($real);

            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . addslashes($filename) . '"');
            header('Content-Length: ' . filesize($real));
            readfile($real);
        } catch (Exception $e) {
            http_response_code(500);
            echo "Download error";
        }
    }
}
