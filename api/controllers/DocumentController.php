<?php

require_once __DIR__ . '/../models/Document.php';

class DocumentController {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

 private function uploadsBasePath(): string {
    
    $base = __DIR__ . '/../../storage/uploads';

    // Opret mappen hvis den mangler
    if (!is_dir($base)) {
        @mkdir($base, 0755, true);
    }

    if (!is_dir($base)) {
        throw new Exception("Missing storage/uploads (path: $base)");
    }

    return rtrim($base, '/');
}

private function deleteRelativeFile(string $pathOrUrl): void {
    $base = $this->uploadsBasePath();

    // Hvis der ligger en fuld URL i DB, så lav den om til en relativ sti
    // fx https://buchdesigns.dk/storage/uploads/applications/23/cv.pdf
    // -> applications/23/cv.pdf
    if (preg_match('~^https?://~i', $pathOrUrl)) {
        $u = parse_url($pathOrUrl);
        $p = $u['path'] ?? '';
        // Find det der kommer efter "/storage/uploads/"
        $needle = '/storage/uploads/';
        $pos = strpos($p, $needle);
        if ($pos !== false) {
            $pathOrUrl = substr($p, $pos + strlen($needle));
        } else {
            // Hvis URL ikke matcher forventet struktur, giv op uden at crashe
            return;
        }
    }

    $relative = ltrim($pathOrUrl, '/');
    $full = $base . '/' . $relative;

    // Hvis filen ikke findes, så skal vi ikke fejle – DB-record skal stadig kunne slettes
    if (!file_exists($full) || !is_file($full)) {
        return;
    }

    // Hvis den ikke kan slettes pga rettigheder, så kast en fejl med sti
    if (!is_writable($full)) {
        throw new Exception("File is not writable: " . $full);
    }

    if (!@unlink($full)) {
        throw new Exception("unlink() failed for: " . $full);
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

    // GET /api/documents/{id}/view
    public function view($id) {
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

            // Bedre for PDF i browser
            header('Content-Type: application/pdf');
            header('X-Content-Type-Options: nosniff');
            header('Content-Disposition: inline; filename="' . addslashes($filename) . '"');
            header('Content-Length: ' . filesize($real));

            readfile($real);
        } catch (Exception $e) {
            http_response_code(500);
            echo "View error";
        }
    }

}
