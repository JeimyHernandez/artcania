<?php
/**
 * ajax/dashboard_stats.php — Artcania
 * Bootstrap propio (sin Router) para evitar conflictos con index.php
 * Coloca este archivo en: C:\xampp\htdocs\artcania\ajax\dashboard_stats.php
 */

// ── Bootstrap mínimo (igual que index.php pero SIN router) ──
define('BASE_PATH', dirname(__DIR__));
define('START_TIME', microtime(true));

$_cfg = require BASE_PATH . '/config/app.php';
date_default_timezone_set($_cfg['timezone'] ?? 'America/El_Salvador');
unset($_cfg);

ini_set('session.cookie_httponly', 1);
ini_set('session.use_strict_mode', 1);
session_start();

// Autoloader
spl_autoload_register(function ($class) {
    $dirs = [
        BASE_PATH . '/app/core/',
        BASE_PATH . '/app/controllers/',
        BASE_PATH . '/app/models/',
    ];
    foreach ($dirs as $dir) {
        $file = $dir . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

// Helpers
foreach (glob(BASE_PATH . '/app/helpers/*.php') as $h) {
    require_once $h;
}

// ── Verificar sesión admin ───────────────────────────────────
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');

if (!Auth::check() || !Auth::isAdmin()) {
    http_response_code(403);
    echo json_encode(['error' => 'No autorizado']);
    exit;
}

// ── Datos para los gráficos ──────────────────────────────────
try {
    $db = Database::getInstance();

    // KPIs
    $kpis = [
        'usuarios_total'    => (int)$db->query('SELECT COUNT(*) FROM usuarios')->fetchColumn(),
        'usuarios_activos'  => (int)$db->query('SELECT COUNT(*) FROM usuarios WHERE activo = 1')->fetchColumn(),
        'usuarios_nuevos'   => (int)$db->query(
            "SELECT COUNT(*) FROM usuarios
             WHERE MONTH(creado_en)=MONTH(NOW()) AND YEAR(creado_en)=YEAR(NOW())"
        )->fetchColumn(),
        'obras'             => (int)$db->query("SELECT COUNT(*) FROM obras WHERE estado='aprobada'")->fetchColumn(),
        'obras_pendientes'  => (int)$db->query("SELECT COUNT(*) FROM obras WHERE estado='pendiente'")->fetchColumn(),
        'artistas'          => (int)$db->query("SELECT COUNT(*) FROM usuarios WHERE rol='artista'")->fetchColumn(),
        'subastas'          => (int)$db->query("SELECT COUNT(*) FROM subastas WHERE estado='activa'")->fetchColumn(),
        'ventas_mes'        => 0,
        'ingresos_total'    => 0,
    ];

    // Intentar tabla ventas (puede no existir)
    try {
        $kpis['ventas_mes']     = (float)$db->query(
            "SELECT COALESCE(SUM(monto_total),0) FROM ventas
             WHERE MONTH(creado_en)=MONTH(NOW()) AND YEAR(creado_en)=YEAR(NOW())"
        )->fetchColumn();
        $kpis['ingresos_total'] = (float)$db->query(
            "SELECT COALESCE(SUM(monto_total),0) FROM ventas"
        )->fetchColumn();
    } catch (\Throwable $e) { /* tabla ventas no existe aún */ }

    // Gráfico 1 — Usuarios por rol (Pie)
    $roles = $db->query(
        "SELECT rol, COUNT(*) AS total FROM usuarios GROUP BY rol ORDER BY total DESC"
    )->fetchAll(PDO::FETCH_ASSOC);

    // Gráfico 2 — Obras por categoría (Bar)
    try {
        $obras = $db->query(
            "SELECT c.nombre AS categoria, COUNT(o.id) AS total
             FROM categorias c
             LEFT JOIN obras o ON o.categoria_id=c.id AND o.estado='aprobada'
             GROUP BY c.id, c.nombre ORDER BY total DESC LIMIT 10"
        )->fetchAll(PDO::FETCH_ASSOC);
    } catch (\Throwable $e) {
        $obras = $db->query(
            "SELECT COALESCE(categoria,'Sin categoría') AS categoria, COUNT(*) AS total
             FROM obras WHERE estado='aprobada' GROUP BY categoria ORDER BY total DESC LIMIT 10"
        )->fetchAll(PDO::FETCH_ASSOC);
    }

    // Gráfico 3 — Subastas por mes (Line)
    $subastas = $db->query(
        "SELECT DATE_FORMAT(creado_en,'%Y-%m') AS mes,
                DATE_FORMAT(creado_en,'%b %Y')  AS mes_label,
                COUNT(*) AS total
         FROM subastas
         WHERE creado_en >= DATE_SUB(NOW(), INTERVAL 12 MONTH)
         GROUP BY mes, mes_label ORDER BY mes ASC"
    )->fetchAll(PDO::FETCH_ASSOC);

    // Gráfico 4 — Ventas por mes (Area)
    $ventas = [];
    try {
        $ventas = $db->query(
            "SELECT DATE_FORMAT(creado_en,'%Y-%m') AS mes,
                    DATE_FORMAT(creado_en,'%b %Y')  AS mes_label,
                    COUNT(*) AS total,
                    COALESCE(SUM(monto_total),0)    AS ingresos
             FROM ventas
             WHERE creado_en >= DATE_SUB(NOW(), INTERVAL 12 MONTH)
             GROUP BY mes, mes_label ORDER BY mes ASC"
        )->fetchAll(PDO::FETCH_ASSOC);
    } catch (\Throwable $e) { /* tabla ventas no existe */ }

    echo json_encode([
        'kpis'     => $kpis,
        'roles'    => $roles,
        'obras'    => $obras,
        'subastas' => $subastas,
        'ventas'   => $ventas,
    ], JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK);

} catch (\Throwable $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
exit;
