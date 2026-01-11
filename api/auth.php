<?php
declare(strict_types=1);

/**
 * Auth helper for JWT stored in HttpOnly cookie ("auth_token").
 * Requires JWT_SECRET to be defined (from secure config).
 */

function base64url_encode(string $data): string {
    return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
}

function base64url_decode(string $data): string {
    $b64 = strtr($data, '-_', '+/');
    $pad = strlen($b64) % 4;
    if ($pad) {
        $b64 .= str_repeat('=', 4 - $pad);
    }
    $decoded = base64_decode($b64, true);
    return $decoded === false ? '' : $decoded;
}

/**
 * Verify JWT (HS256) and return payload as array, or null if invalid.
 */
function verifyJwt(string $jwt): ?array {
    if (!defined('JWT_SECRET')) {
        // Misconfiguration: secret missing
        return null;
    }

    $parts = explode('.', $jwt);
    if (count($parts) !== 3) return null;

    [$headerB64, $payloadB64, $sigB64] = $parts;

    $headerJson  = base64url_decode($headerB64);
    $payloadJson = base64url_decode($payloadB64);

    if ($headerJson === '' || $payloadJson === '') return null;

    $header  = json_decode($headerJson, true);
    $payload = json_decode($payloadJson, true);

    if (!is_array($header) || !is_array($payload)) return null;
    if (($header['alg'] ?? null) !== 'HS256') return null;

    // Recompute signature
    $expectedSig = hash_hmac('sha256', $headerB64 . '.' . $payloadB64, JWT_SECRET, true);
    $expectedSigB64 = base64url_encode($expectedSig);

    if (!hash_equals($expectedSigB64, $sigB64)) return null;

    // Expiry check
    if (isset($payload['exp']) && is_numeric($payload['exp'])) {
        if ((int)$payload['exp'] < time()) return null;
    }

    return $payload;
}

/**
 * Read JWT from HttpOnly cookie.
 */
function getJwtFromCookie(): ?string {
    $token = $_COOKIE['auth_token'] ?? null;
    if (!is_string($token) || $token === '') return null;
    return $token;
}

/**
 * Require auth: returns payload array if valid, otherwise sends 401 and exits.
 */
function requireAuth(): array {
    $jwt = getJwtFromCookie();
    if (!$jwt) {
        http_response_code(401);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['error' => 'Not authenticated']);
        exit;
    }

    $payload = verifyJwt($jwt);
    if (!$payload) {
        http_response_code(401);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['error' => 'Invalid or expired token']);
        exit;
    }

    return $payload;
}

/**
 * Optional auth: returns payload array if valid, otherwise null (no exit).
 */
function optionalAuth(): ?array {
    $jwt = getJwtFromCookie();
    if (!$jwt) return null;
    return verifyJwt($jwt);
}
