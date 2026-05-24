<?php
class View {
    public function render(string $view, array $data = [], string $layout = 'app') {
        extract($data);
        $cfg  = artcania_config();
        $user = Auth::user();
        $flash_success  = Session::getFlash('success');
        $flash_error    = Session::getFlash('error');
        $flash_errors   = Session::getFlash('errors');
        $flash_old      = Session::getFlash('_old');
        $flash_reenviar = Session::getFlash('error_reenviar');
        $csrf_token    = $_SESSION['csrf_token'] ?? '';

        $viewFile = BASE_PATH . '/views/' . str_replace('.', '/', $view) . '.php';
        if (!file_exists($viewFile)) {
            echo "<p style='color:red'>Vista no encontrada: " . htmlspecialchars($view) . "</p>";
            return;
        }

        $layoutFile = BASE_PATH . '/views/layouts/' . $layout . '.php';
        if (!file_exists($layoutFile)) {
            require $viewFile;
            return;
        }

        ob_start();
        require $viewFile;
        $content = ob_get_clean();
        require $layoutFile;
    }
}
