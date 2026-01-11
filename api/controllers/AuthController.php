<?php
class AuthController {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function me() {
    header('Content-Type: application/json; charset=utf-8');
    $user = requireAuth(); // fra auth.php
    echo json_encode(['user' => $user]);
    }

    public function login() {
        $data = json_decode(file_get_contents('php://input'), true);

        if (!isset($data['email'], $data['password'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Email og password påkrævet']);
            return;
        }

        $stmt = $this->pdo->prepare("SELECT * FROM `user` WHERE email = ?");
        $stmt->execute([$data['email']]);
        $user = $stmt->fetch();

        if (!$user) {
            http_response_code(401);
            echo json_encode(['error' => 'Bruger findes ikke']);
            return;
        }

        // NOTE: til eksamen ok, men i “rigtigt” system: password_hash/password_verify
        if ($data['password'] !== $user['password']) {
            http_response_code(401);
            echo json_encode(['error' => 'Ugyldigt password']);
            return;
        }

        // JWT payload
        $payload = [
            'id'        => (int)$user['id'],
            'email'     => $user['email'],
            'full_name' => $user['full_name'],
            'role'      => $user['role'],
            'iat'       => time(),
            'exp'       => time() + 3600, // 1 time
        ];

        $jwt = $this->generateJWT($payload);

        // DEV vs PROD cookie flags
        $isHttps = (
            (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
            || (isset($_SERVER['SERVER_PORT']) && (int)$_SERVER['SERVER_PORT'] === 443)
        );

        // Hvis du vil have "remember me" (persistent cookie), kan du bruge max-age/expires
        $remember = !empty($data['rememberme']);
        $maxAge = $remember ? 60 * 60 * 24 * 7 : 0; // 7 dage, ellers session cookie

        // Sæt JWT i HttpOnly cookie
        setcookie('auth_token', $jwt, [
            'expires'  => $maxAge ? (time() + $maxAge) : 0,
            'path'     => '/',
            'domain'   => '',          // tom = default host (god til localhost)
            'secure'   => $isHttps,     // false på http://localhost, true på https
            'httponly' => true,
            'samesite' => 'Lax',
        ]);

        // Returnér user-info (ingen token nødvendigt i frontend længere)
        echo json_encode([
            'user' => [
                'id'    => (int)$user['id'],
                'name'  => $user['full_name'],
                'email' => $user['email'],
                'role'  => $user['role'],
            ]
        ]);
    }

        public function logout() {
        header('Content-Type: application/json; charset=utf-8');

        // Samme https-detektion som i login (vigtigt så cookie matches korrekt)
        $isHttps = (
            (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
            || (isset($_SERVER['SERVER_PORT']) && (int)$_SERVER['SERVER_PORT'] === 443)
        );

        // Slet cookie ved at sætte den med udløbet tidspunkt
        setcookie('auth_token', '', [
            'expires'  => time() - 3600,
            'path'     => '/',
            'domain'   => '',
            'secure'   => $isHttps,
            'httponly' => true,
            'samesite' => 'Lax',
        ]);

        echo json_encode(['ok' => true]);
    }

private function generateJWT(array $payload): string {
    if (!defined('JWT_SECRET')) {
        throw new RuntimeException('JWT_SECRET is not configured');
    }

    $header = ['alg' => 'HS256', 'typ' => 'JWT'];

    $headerB64  = $this->base64url_encode(json_encode($header));
    $payloadB64 = $this->base64url_encode(json_encode($payload));

    $signature = hash_hmac(
        'sha256',
        $headerB64 . '.' . $payloadB64,
        JWT_SECRET,
        true
    );

    $sigB64 = $this->base64url_encode($signature);

    return $headerB64 . '.' . $payloadB64 . '.' . $sigB64;
}

private function base64url_encode(string $data): string {
    return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
}

}
