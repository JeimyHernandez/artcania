<?php
/**
 * Módulo Producto – obtener una obra via AJAX
 * GET /api/producto/obtener?id=
 */
require_once dirname(__DIR__,2).'/index.php';
header('Content-Type: application/json; charset=utf-8');

$id = (int)($_GET['id'] ?? 0);
if (!$id) { http_response_code(400); echo json_encode(['error'=>'ID requerido']); exit; }

try {
    $db = Database::getInstance();
    $s  = $db->prepare('SELECT o.*,u.nombre as artista,u.avatar as artista_avatar,c.nombre as categoria FROM obras o JOIN usuarios u ON u.id=o.artista_id LEFT JOIN categorias c ON c.id=o.categoria_id WHERE o.id=:id AND o.estado="aprobada" LIMIT 1');
    $s->bindValue(':id',$id,PDO::PARAM_INT); $s->execute();
    $obra = $s->fetch();
    if (!$obra) { http_response_code(404); echo json_encode(['error'=>'Obra no encontrada']); exit; }

    // Etiquetas
    $et = $db->prepare('SELECT e.nombre FROM etiquetas e JOIN obra_etiquetas oe ON oe.etiqueta_id=e.id WHERE oe.obra_id=:id');
    $et->bindValue(':id',$id,PDO::PARAM_INT); $et->execute();
    $obra['etiquetas'] = array_column($et->fetchAll(),'nombre');

    echo json_encode(['ok'=>true,'data'=>$obra], JSON_UNESCAPED_UNICODE);
} catch(Throwable $e) {
    http_response_code(500); echo json_encode(['error'=>'Error al obtener obra']);
}
