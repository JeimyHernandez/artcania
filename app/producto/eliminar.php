<?php
/**
 * Módulo Producto – eliminar obra via AJAX
 * POST /api/producto/eliminar
 */
require_once dirname(__DIR__,2).'/index.php';
header('Content-Type: application/json; charset=utf-8');

if (!Auth::check()) { http_response_code(401); echo json_encode(['error'=>'No autorizado']); exit; }

$token = $_POST['_csrf']??'';
if (!hash_equals($_SESSION['csrf_token']??'',$token)){ http_response_code(419); echo json_encode(['error'=>'CSRF inválido']); exit; }

try {
    $id   = (int)($_POST['id']??0);
    $obra = (new Obra())->find($id);
    if (!$obra){ http_response_code(404); echo json_encode(['error'=>'Obra no encontrada']); exit; }
    $esAdmin = Auth::hasRole('admin');
    if (!$esAdmin && (int)$obra['artista_id']!==Auth::id()){ http_response_code(403); echo json_encode(['error'=>'Sin permiso']); exit; }

    (new Obra())->update($id,['estado'=>'archivada']);
    Logger::accion(Auth::id(),'OBRA_ARCHIVADA',"Obra ID $id archivada por ".Auth::id());
    echo json_encode(['ok'=>true,'mensaje'=>'Obra archivada'], JSON_UNESCAPED_UNICODE);
} catch(Throwable $e) {
    Logger::error('producto/eliminar: '.$e->getMessage());
    http_response_code(500); echo json_encode(['error'=>'Error al eliminar']);
}
