<?php
define('BASE_PATH', __DIR__);
define('START_TIME', microtime(true));

// Load config first (no helpers yet, so require directly)
$_cfg = require BASE_PATH . '/config/app.php';
date_default_timezone_set(isset($_cfg['timezone']) ? $_cfg['timezone'] : 'America/El_Salvador');
unset($_cfg);

// Session
ini_set('session.cookie_httponly', 1);
ini_set('session.use_strict_mode', 1);
session_start();

// CSRF token
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Autoloader: core → controllers → models
spl_autoload_register(function($class) {
    $dirs = array(
        BASE_PATH . '/app/core/',
        BASE_PATH . '/app/controllers/',
        BASE_PATH . '/app/models/',
    );
    foreach ($dirs as $dir) {
        $file = $dir . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

// Helpers
$helperFiles = glob(BASE_PATH . '/app/helpers/*.php');
if ($helperFiles) {
    foreach ($helperFiles as $h) {
        require_once $h;
    }
}

// Route and dispatch
$router = new Router();
require_once BASE_PATH . '/config/routes.php';
$router->dispatch();
