<?php
// Global config cache
function artcania_config(): array {
    static $cfg = null;
    if ($cfg === null) {
        $cfg = require BASE_PATH . '/config/app.php';
    }
    return $cfg;
}

function e($v): string {
    return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');
}

function url(string $path = ''): string {
    $cfg = artcania_config();
    return rtrim($cfg['url'], '/') . '/' . ltrim($path, '/');
}

function asset(string $path): string {
    return url('resources/' . ltrim($path, '/'));
}

function csrf_field(): string {
    $t = $_SESSION['csrf_token'] ?? '';
    return "<input type='hidden' name='_csrf' value='" . e($t) . "'>";
}

function csrf_token(): string {
    return $_SESSION['csrf_token'] ?? '';
}

function old(string $key, $default = ''): string {
    // Try session flash first (before View consumes it), then fall back to global cache
    static $cache = null;
    if ($cache === null) {
        $cache = $_SESSION['_flash']['_old'] ?? [];
    }
    $old = $cache[$key] ?? $default;
    return e($old);
}

function format_date(string $dt, string $fmt = 'd/m/Y H:i'): string {
    return $dt ? date($fmt, strtotime($dt)) : '-';
}

function money($amount): string {
    return '$' . number_format((float)$amount, 2, '.', ',');
}

function truncate(string $str, int $len = 100): string {
    if (mb_strlen($str) > $len) {
        return mb_substr($str, 0, $len) . '...';
    }
    return $str;
}

function media_url(string $path): string {
    return url('media/' . ltrim($path, '/'));
}

function avatar(string $path = ''): string {
    return $path ? media_url($path) : url('resources/img/avatar_default.svg');
}

function dump($v): void {
    echo '<pre style="background:#1a1a2e;color:#a569bd;padding:12px;border-radius:6px;font-size:.8rem;overflow:auto">';
    print_r($v);
    echo '</pre>';
}

function config_get(string $key, $default = null) {
    $db = Database::getInstance();
    try {
        $s = $db->prepare('SELECT valor, tipo FROM configuracion WHERE clave = :c LIMIT 1');
        $s->bindValue(':c', $key);
        $s->execute();
        $r = $s->fetch();
        if (!$r) return $default;
        if ($r['tipo'] === 'int')  return (int)$r['valor'];
        if ($r['tipo'] === 'bool') return (bool)$r['valor'];
        if ($r['tipo'] === 'json') return json_decode($r['valor'], true);
        return $r['valor'];
    } catch (Throwable $e) {
        return $default;
    }
}

function isRole(string $rol): bool {
    return Auth::hasRole($rol);
}

function slug(string $str): string {
    $str = mb_strtolower(trim($str));
    $str = preg_replace('/[áàäâã]/u', 'a', $str);
    $str = preg_replace('/[éèëê]/u',  'e', $str);
    $str = preg_replace('/[íìïî]/u',  'i', $str);
    $str = preg_replace('/[óòöôõ]/u', 'o', $str);
    $str = preg_replace('/[úùüû]/u',  'u', $str);
    $str = preg_replace('/ñ/u',       'n', $str);
    $str = preg_replace('/[^a-z0-9\s-]/', '', $str);
    return preg_replace('/[\s-]+/', '-', trim($str, '-'));
}

function paginate(array $items, int $perPage = 12): array {
    $page   = max(1, (int)($_GET['page'] ?? 1));
    $total  = count($items);
    $pages  = (int)ceil($total / $perPage);
    $offset = ($page - 1) * $perPage;
    return [
        'items'  => array_slice($items, $offset, $perPage),
        'page'   => $page,
        'pages'  => $pages,
        'total'  => $total,
    ];
}
