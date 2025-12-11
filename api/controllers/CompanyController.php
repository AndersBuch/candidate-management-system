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
         // $user = $this->authenticate(); // kun hvis du vil sikre login
        $companyModel = new Company($this->pdo);
        $companies = $companyModel->allWithJobs();
        echo json_encode($companies);
    }

  private function authenticate()
{
    $headers = getallheaders();
    if (!isset($headers['Authorization'])) {
        http_response_code(401);
        echo json_encode(['error' => 'Ikke logget ind']);
        exit;
    }

    $jwt = str_replace('Bearer ', '', $headers['Authorization']);
    $secret = 'DIN_SECRET_KEY';

    // Split JWT
    $parts = explode('.', $jwt);
    if (count($parts) !== 3) {
        http_response_code(401);
        echo json_encode(['error' => 'Ugyldigt token format']);
        exit;
    }

    [$header, $payload, $signature] = $parts;

    // Validér signaturen
    $validSig = $this->base64url_encode(
        hash_hmac('sha256', "$header.$payload", $secret, true)
    );

    if ($signature !== $validSig) {
        http_response_code(401);
        echo json_encode(['error' => 'Ugyldigt token']);
        exit;
    }

    // Dekod payload og returnér brugerinfo
    $user = json_decode($this->base64url_decode($payload), true);

    // Optionelt: tjek udløbstid
    if (isset($user['exp']) && time() > $user['exp']) {
        http_response_code(401);
        echo json_encode(['error' => 'Token udløbet']);
        exit;
    }


    return $user;
}
private function base64url_encode($data) {
    return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
}

private function base64url_decode($data) {
    return base64_decode(strtr($data, '-_', '+/'));
}



}
