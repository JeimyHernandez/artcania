<?php
/**
 * Módulo Producto – actualizar obra via AJAX
 * POST /api/producto/actualizar
 */
require_once dirname(__DIR__,2).'/index.php';
header('Content-Type: application/json; charset=utf-8');

if (!Auth::check()) { http_response_code(401); echo json_encode(['error'=>'No autorizado']); exit; }

$token = $_POST['_csrf']??'';
if (!hash_equals($_SESSION['csrf_token']??'',$token)){ http_response_code(419); echo json_encode(['error'=>'CSRF inválido']); exit; }

try {
    $id   = (int)($_POST['id']??0);
    $obra = (new Obra())->find($id);
    if (!$obra || (int)$obra['artista_id']!==Auth::id()) { http_response_code(403); echo json_encode(['error'=>'Sin permiso']); exit; }

    $d = [];
    if (!empty($_POST['titulo']))      $d['titulo']      = htmlspecialchars(trim($_POST['titulo']),ENT_QUOTES,'UTF-8');
    if (!empty($_POST['descripcion'])) $d['descripcion'] = htmlspecialchars(trim($_POST['descripcion']),ENT_QUOTES,'UTF-8');
    if (isset($_POST['precio']))       $d['precio']      = (float)$_POST['precio'] ?: null;

    if (!empty($_FILES['imagen']['name'])) {
        $up = new Upload('Originales/imagen/Obras_digitales');
        $d['imagen_principal'] = $up->handle($_FILES['imagen']);
    }
    (new Obra())->guardar($id, $d);
    Logger::accion(Auth::id(),'OBRA_ACTUALIZADA',"Obra ID $id actualizada");
    echo json_encode(['ok'=>true,'mensaje'=>'Obra actualizada'], JSON_UNESCAPED_UNICODE);
} catch(Throwable $e) {
    Logger::error('producto/actualizar: '.$e->getMessage());
    http_response_code(500); echo json_encode(['error'=>'Error al actualizar']);
}
