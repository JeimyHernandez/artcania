<?php
/**
 * Controller.php – PHP 7.4
 * Base con middleware de roles estricto y respuestas AJAX.
 */
class Controller
{
    protected function view(string $view, array $data = array(), string $layout = 'app'): void
    {
        extract($data);
        (new View())->render($view, $data, $layout);
    }

    protected function redirect(string $path): void
    {
        $cfg = artcania_config();
        $url = rtrim($cfg['url'], '/') . '/' . ltrim($path, '/');
        header('Location: ' . $url, true, 302);
        exit;
    }

    protected function json($data, int $code = 200): void
    {
        http_response_code($code);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }

    protected function csrfCheck(): void
    {
        $token = $_POST['_csrf'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
        if (!hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
            if ($this->isAjax()) {
                $this->json(array('error' => 'Token de seguridad inválido. Recarga la página.'), 419);
            }
            Session::flash('error', 'La solicitud expiró. Intenta de nuevo.');
            http_response_code(419);
            $ref = $_SERVER['HTTP_REFERER'] ?? artcania_config()['url'] . '/login';
            header('Location: ' . $ref);
            exit;
        }
    }

    // ── Middleware de autenticación y roles ───────────────────────────────────

    protected function requireAuth(string $rol = ''): void
    {
        if (!Auth::check()) {
            if ($this->isAjax()) $this->json(array('error' => 'No autenticado.'), 401);
            Session::flash('error', 'Debes iniciar sesión.');
            $this->redirect('login');
        }
        if ($rol !== '' && !Auth::hasRole($rol)) {
            $this->deny();
        }
    }

    protected function requireRole(string $rol): void
    {
        $this->requireAuth($rol);
    }

    protected function requireAdmin(): void
    {
        if (!Auth::check()) {
            if ($this->isAjax()) $this->json(array('error' => 'No autenticado.'), 401);
            Session::flash('error', 'Debes iniciar sesión.');
            $this->redirect('login');
        }
        if (!Auth::isAdmin()) $this->deny();
    }

    protected function requireAnyRole(array $roles): void
    {
        if (!Auth::check()) {
            if ($this->isAjax()) $this->json(array('error' => 'No autenticado.'), 401);
            Session::flash('error', 'Debes iniciar sesión.');
            $this->redirect('login');
        }
        $rol = Auth::rol();
        if ($rol !== 'admin' && !in_array($rol, $roles, true)) {
            $this->deny();
        }
    }

    // ── Descargas ─────────────────────────────────────────────────────────────

    protected function downloadPDF(string $content, string $filename): void
    {
        $filename = preg_replace('/[^a-zA-Z0-9_\-\.]/', '_', $filename);
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        echo $content;
        exit;
    }

    protected function downloadExcel(string $content, string $filename): void
    {
        $filename = preg_replace('/[^a-zA-Z0-9_\-\.]/', '_', $filename);
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Content-Length: ' . strlen($content));
        echo $content;
        exit;
    }

    // ── Helpers privados ──────────────────────────────────────────────────────

    private function deny(): void
    {
        if ($this->isAjax()) $this->json(array('error' => 'Acceso denegado.'), 403);
        http_response_code(403);
        (new View())->render('errors/403', array(), 'public');
        exit;
    }

    private function isAjax(): bool
    {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH'])
            && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }
}
