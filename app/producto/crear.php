<?php
/**
 * Módulo Producto – crear obra via AJAX (artista autenticado)
 * POST /api/producto/crear
 */
require_once dirname(__DIR__,2).'/index.php';
header('Content-Type: application/json; charset=utf-8');

if (!Auth::check() || !Auth::hasRole('artista')) { http_response_code(401); echo json_encode(['error'=>'No autorizado']); exit; }
if ($_SERVER['REQUEST_METHOD']!=='POST') { http_response_code(405); echo json_encode(['error'=>'Método no permitido']); exit; }

$token = $_POST['_csrf'] ?? '';
if (!hash_equals($_SESSION['csrf_token']??'', $token)) { http_response_code(419); echo json_encode(['error'=>'CSRF inválido']); exit; }

try {
    $v = new Validation();
    $d = [
        'titulo'      => htmlspecialchars(trim($_POST['titulo']??''), ENT_QUOTES, 'UTF-8'),
        'descripcion' => htmlspecialchars(trim($_POST['descripcion']??''), ENT_QUOTES, 'UTF-8'),
        'precio'      => $_POST['precio']??null,
        'categoria_id'=> (int)($_POST['categoria_id']??0) ?: null,
        'tecnica'     => htmlspecialchars(trim($_POST['tecnica']??''), ENT_QUOTES, 'UTF-8'),
    ];
    if (!$v->check($d,['titulo'=>'required|min:3|max:200','descripcion'=>'required|min:10'])){
        http_response_code(422); echo json_encode(['error'=>$v->errors()]); exit;
    }
    $d['artista_id'] = Auth::id(); $d['estado']='pendiente';
    if (!empty($_FILES['imagen']['name'])) {
        $up = new Upload('Originales/imagen/Obras_digitales');
        $d['imagen_principal'] = $up->handle($_FILES['imagen']);
    }
    $id = (new Obra())->crear($d);
    Logger::accion(Auth::id(),'OBRA_CREADA',"Obra ID $id vía API módulo");
    echo json_encode(['ok'=>true,'id'=>$id,'mensaje'=>'Obra enviada para revisión'], JSON_UNESCAPED_UNICODE);
} catch(Throwable $e) {
    Logger::error('producto/crear: '.$e->getMessage());
    http_response_code(500); echo json_encode(['error'=>'Error al crear obra']);
}
