<?php
class Response {
    public static function json($data, int $code = 200) {
        http_response_code($code);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE); exit;
    }
    public static function redirect(string $url) { header('Location: '.$url); exit; }
    public static function abort(int $code) { http_response_code($code); exit; }
    public static function noCache() {
        header('Cache-Control: no-store, no-cache, must-revalidate');
        header('Pragma: no-cache'); header('Expires: 0');
    }
}
