<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');

define('BASE_PATH', __DIR__);

echo "<pre style='font-family:monospace;font-size:13px;padding:20px;background:#141b2d;color:#f4f7fb'>";
echo "<h2 style='color:#7b9ef9'>🎨 Artcania — Diagnóstico</h2>\n";

// Autoloader y helpers
spl_autoload_register(function($class) {
    foreach ([BASE_PATH.'/app/core/', BASE_PATH.'/app/controllers/', BASE_PATH.'/app/models/'] as $dir) {
        $f = $dir . $class . '.php';
        if (file_exists($f)) { require_once $f; return; }
    }
});
foreach (glob(BASE_PATH . '/app/helpers/*.php') as $h) require_once $h;
session_start();
if (empty($_SESSION['csrf_token'])) $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

// 1. Configuración OAuth Mail
$cfg = artcania_config();
$mc  = $cfg['mail'];
echo "<b style='color:#a6bdff'>── Configuración Correo OAuth 2.0 ──────────</b>\n";
echo "  oauth_email: " . ($mc['oauth_email'] ?? '(no definido)') . "\n";
echo "  oauth_client_id: " . (!empty($mc['oauth_client_id']) ? '<span style="color:#4ade80">✓ configurado</span>' : '<span style="color:#f87171">✗ VACÍO</span>') . "\n";
echo "  oauth_client_secret: " . (!empty($mc['oauth_client_secret']) ? '<span style="color:#4ade80">✓ configurado</span>' : '<span style="color:#f87171">✗ VACÍO</span>') . "\n";
echo "  oauth_refresh_token: " . (!empty($mc['oauth_refresh_token'])
    ? '<span style="color:#4ade80">✓ configurado ('.strlen($mc['oauth_refresh_token']).' caracteres)</span>'
    : '<span style="color:#f87171">✗ VACÍO — abre /oauth_mail_setup.php para autorizarlo</span>') . "\n\n";

// 2. DB
echo "<b style='color:#a6bdff'>── Base de datos ───────────────────────────</b>\n";
try {
    $db = Database::getInstance();
    $v  = $db->query('SELECT VERSION()')->fetchColumn();
    echo "  <span style='color:#4ade80'>✓ Conectado — MySQL $v</span>\n\n";
} catch (Throwable $e) {
    echo "  <span style='color:#f87171'>✗ " . htmlspecialchars($e->getMessage()) . "</span>\n\n";
}

// 3. Test de correo OAuth
echo "<b style='color:#a6bdff'>── Test Correo Gmail API (OAuth 2.0) ───────</b>\n";
$testResult = Mailer::testConexion();
foreach ($testResult as $k => $v) {
    if ($k === 'ok') continue;
    if ($k === 'error' && $v === null) continue;
    echo "  $k: " . htmlspecialchars((string)$v) . "\n";
}
if ($testResult['ok']) {
    echo "  <span style='color:#4ade80'>✓ OAuth token obtenido correctamente</span>\n";
    $testTo = $_GET['test_email'] ?? '';
    if ($testTo && filter_var($testTo, FILTER_VALIDATE_EMAIL)) {
        $sent = Mailer::send(
            $testTo,
            '✅ Test Artcania OAuth Mail',
            '<h2 style="color:#1a1a2e">¡Funciona!</h2><p>El correo de Artcania vía Gmail API OAuth 2.0 está configurado correctamente.</p>'
        );
        echo $sent
            ? "  <span style='color:#4ade80'>✓ Correo de prueba enviado a " . htmlspecialchars($testTo) . "</span>\n"
            : "  <span style='color:#f87171'>✗ Error al enviar (revisa logs/errores.log)</span>\n";
    } else {
        echo "  Para enviar un correo de prueba visita:\n";
        echo "  <a style='color:#7b9ef9' href='?test_email=tu@correo.com'>?test_email=tu@correo.com</a>\n";
    }
} elseif (!empty($testResult['error'])) {
    echo "  <span style='color:#f87171'>✗ " . htmlspecialchars($testResult['error']) . "</span>\n";
}
echo "\n";

// 4. Google Login config
echo "<b style='color:#a6bdff'>── Google Login ────────────────────────────</b>\n";
$gc = $cfg['google_login'] ?? [];
echo "  client_id: " . (!empty($gc['client_id']) ? '<span style="color:#4ade80">✓ configurado</span>' : '<span style="color:#f87171">✗ VACÍO</span>') . "\n";
echo "  redirect_uri: " . htmlspecialchars($gc['redirect_uri'] ?? '(no definido)') . "\n\n";

// 5. Modelos clave
echo "<b style='color:#a6bdff'>── Modelos ─────────────────────────────────</b>\n";
foreach (['Obra','Artista','Subasta','Usuario','Comentario'] as $cls) {
    echo "  " . (class_exists($cls) ? "<span style='color:#4ade80'>✓</span>" : "<span style='color:#f87171'>✗</span>") . " $cls\n";
}

echo "\n<span style='color:#a6bdff'>Debug listo.</span></pre>";
