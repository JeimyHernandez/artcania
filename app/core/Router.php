<?php
class Router {

    private $routes = array();

    /**
     * @param string $uri
     * @param string $action
     */
    public function get($uri, $action) {
        $this->add('GET', $uri, $action);
    }

    /**
     * @param string $uri
     * @param string $action
     */
    public function post($uri, $action) {
        $this->add('POST', $uri, $action);
    }

    /**
     * @param string $method
     * @param string $uri
     * @param string $action
     */
    private function add($method, $uri, $action) {
        $uri     = trim($uri, '/');
        $pattern = preg_replace('/\{[^}]+\}/', '([^/]+)', $uri);
        $this->routes[] = array(
            'method'  => $method,
            'uri'     => $uri,
            'pattern' => $pattern,
            'action'  => $action,
        );
    }

    public function dispatch() {
        $method = $_SERVER['REQUEST_METHOD'];

        // --- Resolve URI ---
        $requestUri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '/';

        // Strip query string
        $qpos = strpos($requestUri, '?');
        if ($qpos !== false) {
            $requestUri = substr($requestUri, 0, $qpos);
        }

        // URL decode
        $requestUri = rawurldecode($requestUri);

        // Get base path (e.g. /artcania) from SCRIPT_NAME
        // SCRIPT_NAME = /artcania/index.php → base = /artcania
        $scriptName = isset($_SERVER['SCRIPT_NAME']) ? $_SERVER['SCRIPT_NAME'] : '';
        $basePath   = rtrim(str_replace('\\', '/', dirname($scriptName)), '/');
        // basePath is now '' (root) or '/artcania' (subdirectory)

        // Strip basePath from requestUri
        if ($basePath !== '' && strpos($requestUri, $basePath) === 0) {
            $requestUri = substr($requestUri, strlen($basePath));
        }

        // Normalize: remove leading/trailing slashes
        $uri = trim($requestUri, '/');

        // --- Match routes ---
        foreach ($this->routes as $route) {

            if ($route['method'] !== $method) {
                continue;
            }

            // Root route ''
            if ($route['pattern'] === '') {
                if ($uri === '') {
                    $this->call($route['action'], array());
                    return;
                }
                continue;
            }

            $regex = '#^' . $route['pattern'] . '$#u';
            if (preg_match($regex, $uri, $matches)) {
                array_shift($matches); // remove full match
                $this->call($route['action'], $matches);
                return;
            }
        }

        $this->show404();
    }

    private function call($action, $params) {
        $parts = explode('@', $action);
        if (count($parts) !== 2) {
            $this->show404();
            return;
        }

        $ctrlName   = $parts[0];
        $methodName = $parts[1];

        if (!class_exists($ctrlName)) {
            error_log("Router: class $ctrlName not found");
            $this->show404();
            return;
        }

        if (!method_exists($ctrlName, $methodName)) {
            error_log("Router: method $ctrlName::$methodName not found");
            $this->show404();
            return;
        }

        try {
            $obj = new $ctrlName();
            call_user_func_array(array($obj, $methodName), $params);
        } catch (Exception $e) {
            error_log('Router dispatch error: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine());
            $this->show500($e->getMessage());
        } catch (Throwable $e) {
            error_log('Router dispatch throwable: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine());
            $this->show500($e->getMessage());
        }
    }

    private function show404() {
        http_response_code(404);
        $f = BASE_PATH . '/views/errors/404.php';
        if (file_exists($f)) {
            require $f;
        } else {
            echo '<!DOCTYPE html><html><body style="font-family:sans-serif;text-align:center;padding:50px">';
            echo '<h1 style="color:#6c3483">404</h1><p>Página no encontrada.</p>';
            echo '<a href="' . $this->baseUrl() . '">Volver al inicio</a></body></html>';
        }
    }

    private function show500($msg = '') {
        http_response_code(500);
        $f = BASE_PATH . '/views/errors/500.php';
        if (file_exists($f)) {
            require $f;
        } else {
            echo '<!DOCTYPE html><html><body style="font-family:sans-serif;text-align:center;padding:50px">';
            echo '<h1 style="color:#e74c3c">500</h1><p>Error interno del servidor.</p>';
            if (function_exists('artcania_config')) {
                $cfg = artcania_config();
                if (isset($cfg['env']) && $cfg['env'] === 'development') {
                    echo '<pre style="text-align:left;background:#fff0f0;padding:10px;border-radius:4px">' . htmlspecialchars($msg) . '</pre>';
                }
            }
            echo '</body></html>';
        }
    }

    private function baseUrl() {
        $scriptName = isset($_SERVER['SCRIPT_NAME']) ? $_SERVER['SCRIPT_NAME'] : '/';
        $base       = rtrim(str_replace('\\', '/', dirname($scriptName)), '/');
        $scheme     = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $host       = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost';
        return $scheme . '://' . $host . $base . '/';
    }
}
