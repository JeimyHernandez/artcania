<?php
/**
 * ajax/get_subcategorias.php — Artcania
 * Coloca en: C:\xampp\htdocs\artcania\ajax\get_subcategorias.php
 */

define('BASE_PATH', dirname(__DIR__));
define('START_TIME', microtime(true));

$_cfg = require BASE_PATH . '/config/app.php';
date_default_timezone_set($_cfg['timezone'] ?? 'America/El_Salvador');
unset($_cfg);

ini_set('session.cookie_httponly', 1);
session_start();

spl_autoload_register(function ($class) {
    foreach ([BASE_PATH.'/app/core/', BASE_PATH.'/app/models/'] as $dir) {
        $f = $dir . $class . '.php';
        if (file_exists($f)) { require_once $f; return; }
    }
});

foreach (glob(BASE_PATH . '/app/helpers/*.php') as $h) require_once $h;

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');

$categoriaId = (int)($_GET['categoria_id'] ?? 0);
if ($categoriaId <= 0) { echo json_encode([]); exit; }

try {
    $db = Database::getInstance();

    // Intentar tabla subcategorias
    try {
        $s = $db->prepare(
            'SELECT id, nombre FROM subcategorias
             WHERE categoria_id = :cid AND activa = 1 ORDER BY orden ASC, nombre ASC'
        );
        $s->execute([':cid' => $categoriaId]);
        $rows = $s->fetchAll(PDO::FETCH_ASSOC);
    } catch (\Throwable $e) {
        $rows = [];
    }

    echo json_encode($rows, JSON_UNESCAPED_UNICODE);

} catch (\Throwable $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
exit;
