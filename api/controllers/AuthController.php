<?php
class AuthController {
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function login() {
        $data = json_decode(file_get_contents('php://input'), true);

        if (!isset($data['email'], $data['password'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Email og password påkrævet']);
            return;
        }

        $stmt = $this->pdo->prepare("SELECT * FROM User WHERE email = ?");
        $stmt->execute([$data['email']]);
        $user = $stmt->fetch();

        if (!$user) {
            http_response_code(401);
            echo json_encode(['error' => 'Bruger findes ikke', 'debug' => $data['email']]);
            return;
        }

        // Direkte sammenligning uden hash
        if ($data['password'] !== $user['password']) {
            http_response_code(401);
            echo json_encode(['error' => 'Ugyldigt password']);
            return;
        }

        // Lav JWT-token
        $payload = [
            'id' => $user['id'],
            'email' => $user['email'],
            'full_name' => $user['full_name'],
            'role' => $user['role'],
            'iat' => time(),
            'exp' => time() + 3600,
        ];

        $jwt = $this->generateJWT($payload);

        echo json_encode(['token' => $jwt, 'user' => ['id'=>$user['id'],'name'=>$user['full_name'],'email'=>$user['email']]]);
    }

    private function generateJWT($payload) {
        $header = base64_encode(json_encode(['alg'=>'HS256','typ'=>'JWT']));
        $payload = base64_encode(json_encode($payload));
        $secret = 'DIN_SECRET_KEY';
        $signature = hash_hmac('sha256', "$header.$payload", $secret, true);
        return "$header.$payload." . base64_encode($signature);
    }
}
