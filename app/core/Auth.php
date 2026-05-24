<?php
/**
 * Auth.php – PHP 7.4
 * Autenticación con: 3 intentos → bloqueo 1 hora, mensajes específicos por caso.
 */
class Auth
{
    public static function attempt(string $email, string $pass): bool
    {
        $cfg = artcania_config();
        $u   = (new Usuario())->porEmail($email);

        if (!$u) {
            // No revelar si el email existe
            Session::flash('error', 'Credenciales incorrectas.');
            return false;
        }

        if (!$u['verificado']) {
            Session::flash('error', 'Debes verificar tu correo antes de iniciar sesión. Revisa tu bandeja de entrada y la carpeta spam.');
            Session::flash('error_reenviar', true); // indica que hay que mostrar el link de reenvío
            return false;
        }

        if (!$u['activo']) {
            Session::flash('error', 'Tu cuenta ha sido desactivada. Contacta al administrador.');
            return false;
        }

        // Cuenta bloqueada?
        if (!empty($u['bloqueado_hasta'])) {
            $hasta = strtotime($u['bloqueado_hasta']);
            if ($hasta > time()) {
                $min = ceil(($hasta - time()) / 60);
                Session::flash('error', "Cuenta bloqueada por demasiados intentos. Intenta de nuevo en {$min} minuto(s).");
                return false;
            }
            // Bloqueo expirado → resetear
            (new Usuario())->resetIntentos((int)$u['id']);
            $u = (new Usuario())->porEmail($email);
        }

        $pepper = $cfg['security']['pepper'] ?? '';

        if (password_verify($pass . $pepper, $u['password'])) {
            (new Usuario())->resetIntentos((int)$u['id']);
            Database::getInstance()
                ->prepare('UPDATE usuarios SET ultimo_login = NOW() WHERE id = :id')
                ->execute(array(':id' => (int)$u['id']));

            $_SESSION['user'] = array(
                'id'     => (int)$u['id'],
                'nombre' => $u['nombre'],
                'email'  => $u['email'],
                'rol'    => $u['rol'],
                'avatar' => $u['avatar'] ?? '',
            );
            session_regenerate_id(true);
            Logger::accion((int)$u['id'], 'LOGIN', 'Login desde IP: ' . self::clientIP());
            return true;
        }

        // Intento fallido
        $max      = (int)($cfg['security']['login_max_tries'] ?? 3);
        $intentos = (new Usuario())->incrementarIntentos((int)$u['id'], $max);
        $restantes = max(0, $max - $intentos);

        if ($restantes <= 0) {
            Session::flash('error', 'Cuenta bloqueada por 1 hora por múltiples intentos fallidos.');
        } else {
            Session::flash('error', "Credenciales incorrectas. Te quedan {$restantes} intento(s) antes de ser bloqueado.");
        }

        Logger::accion((int)$u['id'], 'LOGIN_FALLIDO', 'Intento desde IP: ' . self::clientIP());
        return false;
    }

    public static function check(): bool   { return isset($_SESSION['user']['id']); }
    public static function user(): ?array  { return $_SESSION['user'] ?? null; }
    public static function id(): ?int      { return isset($_SESSION['user']['id']) ? (int)$_SESSION['user']['id'] : null; }
    public static function rol(): ?string  { return $_SESSION['user']['rol'] ?? null; }

    public static function hasRole(string $rol): bool
    {
        $r = $_SESSION['user']['rol'] ?? null;
        return $r === 'admin' || $r === $rol;
    }

    public static function isAdmin():   bool { return ($_SESSION['user']['rol'] ?? '') === 'admin'; }
    public static function isArtista(): bool { return ($_SESSION['user']['rol'] ?? '') === 'artista'; }
    public static function isCurador(): bool { return ($_SESSION['user']['rol'] ?? '') === 'curador'; }
    public static function isUsuario(): bool { return ($_SESSION['user']['rol'] ?? '') === 'usuario'; }

    public static function logout(): void
    {
        if (self::check()) Logger::accion(self::id(), 'LOGOUT', 'Cierre de sesión');
        $_SESSION = array();
        if (ini_get('session.use_cookies')) {
            $p = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $p['path'], $p['domain'], $p['secure'], $p['httponly']);
        }
        session_destroy();
    }

    public static function hashPassword(string $plain): string
    {
        $pepper = artcania_config()['security']['pepper'] ?? '';
        return password_hash($plain . $pepper, PASSWORD_BCRYPT, array('cost' => 12));
    }

    private static function clientIP(): string
    {
        foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'REMOTE_ADDR') as $k) {
            if (!empty($_SERVER[$k])) {
                $ip = filter_var(trim(explode(',', $_SERVER[$k])[0]), FILTER_VALIDATE_IP);
                if ($ip) return $ip;
            }
        }
        return '0.0.0.0';
    }
}
