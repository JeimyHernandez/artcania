<?php
/**
 * Session.php – PHP 7.4
 */
class Session
{
    public static function flash(string $key, $value = null)
    {
        if ($value !== null) {
            $_SESSION['_flash'][$key] = $value;
            return null;
        }
        $v = $_SESSION['_flash'][$key] ?? null;
        unset($_SESSION['_flash'][$key]);
        return $v;
    }

    public static function getFlash(string $key) { return self::flash($key); }
    public static function hasFlash(string $key): bool { return isset($_SESSION['_flash'][$key]); }
    public static function set(string $key, $value): void { $_SESSION[$key] = $value; }
    public static function get(string $key, $default = null) { return $_SESSION[$key] ?? $default; }
    public static function forget(string $key): void { unset($_SESSION[$key]); }
    public static function has(string $key): bool { return isset($_SESSION[$key]); }
}
