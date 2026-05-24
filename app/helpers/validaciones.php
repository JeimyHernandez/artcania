<?php
function validarEmail(string $email): bool { return (bool)filter_var($email, FILTER_VALIDATE_EMAIL); }
function validarPassword(string $pass): bool { return strlen($pass)>=8 && preg_match('/[A-Z]/',$pass) && preg_match('/[a-z]/',$pass) && preg_match('/\d/',$pass); }
function sanitizeInput(string $input): string { return htmlspecialchars(strip_tags(trim($input)), ENT_QUOTES, 'UTF-8'); }
function validarCsrf(string $token): bool { return hash_equals($_SESSION['csrf_token']??'', $token); }
function validarMimeImage(string $tmpPath): bool {
    $allowed = ['image/jpeg','image/png','image/gif','image/webp'];
    return in_array(mime_content_type($tmpPath), $allowed);
}
function validarExtension(string $filename, array $allowed=['jpg','jpeg','png','gif','webp']): bool {
    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    return in_array($ext, $allowed);
}
