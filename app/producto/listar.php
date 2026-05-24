<?php
/**
 * Módulo Producto – listar obras via AJAX
 * GET /api/producto/listar?cat=&q=&page=
 */
require_once dirname(__DIR__,2).'/index.php';
header('Content-Type: application/json; charset=utf-8');

$cat   = (int)($_GET['cat']  ?? 0);
$q     = trim(htmlspecialchars($_GET['q'] ?? '', ENT_QUOTES, 'UTF-8'));
$page  = max(1,(int)($_GET['page'] ?? 1));
$limit = 12; $offset = ($page-1)*$limit;

try {
    $db   = Database::getInstance();
    $where= "o.estado='aprobada'";
    $params=[];
    if ($cat)  { $where .= " AND o.categoria_id=:cat"; $params[':cat']=$cat; }
    if ($q)    { $where .= " AND (o.titulo LIKE :q OR o.descripcion LIKE :q)"; $params[':q']="%$q%"; }

    $sql = "SELECT o.*,u.nombre as artista,c.nombre as categoria FROM obras o JOIN usuarios u ON u.id=o.artista_id LEFT JOIN categorias c ON c.id=o.categoria_id WHERE $where ORDER BY o.creado_en DESC LIMIT :l OFFSET :off";
    $s   = $db->prepare($sql);
    foreach ($params as $k=>$v) $s->bindValue($k,$v);
    $s->bindValue(':l',$limit,PDO::PARAM_INT);
    $s->bindValue(':off',$offset,PDO::PARAM_INT);
    $s->execute();
    $obras = $s->fetchAll();

    $count = $db->prepare("SELECT COUNT(*) FROM obras o WHERE $where");
    foreach ($params as $k=>$v) $count->bindValue($k,$v);
    $count->execute(); $total = (int)$count->fetchColumn();

    echo json_encode(['ok'=>true,'data'=>$obras,'total'=>$total,'pages'=>ceil($total/$limit),'page'=>$page], JSON_UNESCAPED_UNICODE);
} catch(Throwable $e) {
    http_response_code(500);
    echo json_encode(['ok'=>false,'error'=>'Error al listar obras']);
}
