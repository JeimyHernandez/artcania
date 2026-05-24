<?php
/**
 * Módulo Producto – cargar selects (categorías y etiquetas) via AJAX
 * GET /api/producto/cargar_selects
 */
require_once dirname(__DIR__,2).'/index.php';
header('Content-Type: application/json; charset=utf-8');

try {
    $categorias = (new Categoria())->activas();
    $etiquetas  = (new Etiqueta())->all();
    echo json_encode(['ok'=>true,'categorias'=>$categorias,'etiquetas'=>$etiquetas], JSON_UNESCAPED_UNICODE);
} catch(Throwable $e) {
    http_response_code(500); echo json_encode(['error'=>'Error al cargar datos']);
}
