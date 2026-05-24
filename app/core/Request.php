<?php
class Request {
    public static function input(string $key, $default = null) {
        return self::sanitize($_POST[$key] ?? $_GET[$key] ?? $default);
    }
    public static function post(string $key, $default = null) {
        return self::sanitize($_POST[$key] ?? $default);
    }
    public static function get(string $key, $default = null) {
        return self::sanitize($_GET[$key] ?? $default);
    }
    public static function only(array $keys) {
        $r = [];
        foreach ($keys as $k) $r[$k] = self::input($k);
        return $r;
    }
    public static function all() {
        return array_merge(
            array_map([self::class,'sanitize'], $_GET),
            array_map([self::class,'sanitize'], $_POST)
        );
    }
    public static function isPost() { return $_SERVER['REQUEST_METHOD'] === 'POST'; }
    public static function isAjax() { return ($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '') === 'XMLHttpRequest'; }
    public static function file(string $key) { return $_FILES[$key] ?? null; }

    private static function sanitize($v) {
        if (is_string($v)) return trim($v);
        return $v;
    }
}
