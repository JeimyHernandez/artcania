<?php
/**
 * ajax/get_tecnicas.php — Artcania
 * Coloca en: C:\xampp\htdocs\artcania\ajax\get_tecnicas.php
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

$subcategoriaId = isset($_GET['subcategoria_id']) && $_GET['subcategoria_id'] !== ''
    ? (int)$_GET['subcategoria_id'] : null;

try {
    $db = Database::getInstance();

    try {
        if ($subcategoriaId) {
            $s = $db->prepare(
                'SELECT id, nombre FROM tecnicas_artisticas
                 WHERE subcategoria_id = :sid AND activa = 1 ORDER BY orden ASC, nombre ASC'
            );
            $s->execute([':sid' => $subcategoriaId]);
        } else {
            $s = $db->query(
                'SELECT id, nombre FROM tecnicas_artisticas
                 WHERE activa = 1 ORDER BY orden ASC, nombre ASC'
            );
        }
        $rows = $s->fetchAll(PDO::FETCH_ASSOC);
    } catch (\Throwable $e) {
        // Fallback estático si la tabla no existe
        $rows = [
            ['id'=>0,'nombre'=>'Óleo'],
            ['id'=>0,'nombre'=>'Acuarela'],
            ['id'=>0,'nombre'=>'Acrílico'],
            ['id'=>0,'nombre'=>'Arte Digital'],
            ['id'=>0,'nombre'=>'Fotografía'],
            ['id'=>0,'nombre'=>'Escultura'],
            ['id'=>0,'nombre'=>'Grabado'],
            ['id'=>0,'nombre'=>'Ilustración'],
            ['id'=>0,'nombre'=>'Técnica Mixta'],
        ];
    }

    echo json_encode($rows, JSON_UNESCAPED_UNICODE);

} catch (\Throwable $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
exit;
