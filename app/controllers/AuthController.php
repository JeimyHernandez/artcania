<?php
/**
 * AuthController.php – PHP 7.4
 * Login normal + recuperar contraseña + Login con Google.
 */
class AuthController extends Controller
{
    // ── Formularios ──────────────────────────────────────────────────────────

    public function loginForm(): void
    {
        if (Auth::check()) $this->redirectByRole(Auth::rol());
        $this->view('auth/login', array(), 'auth');
    }

    public function registerForm(): void
    {
        if (Auth::check()) $this->redirectByRole(Auth::rol());
        $this->view('auth/registro', array(), 'auth');
    }

    public function forgotForm(): void
    {
        $this->view('auth/recuperar_password', array('modo' => 'solicitar'), 'auth');
    }

    public function resetForm(string $token): void
    {
        if (!(new Usuario())->verificarTokenReset($token)) {
            Session::flash('error', 'El enlace es inválido o ha expirado. Solicita uno nuevo.');
            $this->redirect('recuperar');
        }
        $this->view('auth/recuperar_password', array('token' => $token, 'modo' => 'reset'), 'auth');
    }

    // ── Login con email/contraseña ────────────────────────────────────────────

    public function login(): void
    {
        $this->csrfCheck();
        $email = trim((string)Request::input('email'));
        $pass  = (string)($_POST['password'] ?? ''); // raw - never sanitize passwords

        if (empty($email) || empty($pass)) {
            Session::flash('error', 'Correo y contraseña son requeridos.');
            $this->redirect('login');
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            Session::flash('error', 'Formato de correo inválido.');
            $this->redirect('login');
        }

        if (Auth::attempt($email, $pass)) {
            $this->redirectByRole(Auth::rol());
        }
        // Auth::attempt ya pone el flash de error con detalle
        $this->redirect('login');
    }

    // ── Registro ──────────────────────────────────────────────────────────────

    public function register(): void
    {
        $this->csrfCheck();
        $data = Request::only(array('nombre', 'email', 'rol'));
        $data['password']         = $_POST['password'] ?? '';
        $data['password_confirm'] = $_POST['password_confirm'] ?? '';
        $data['nombre'] = strip_tags(trim($data['nombre'] ?? ''));
        $data['email']  = strtolower(trim($data['email'] ?? ''));

        $rolesPermitidos = array('usuario', 'artista');
        $data['rol'] = in_array($data['rol'] ?? 'usuario', $rolesPermitidos)
            ? $data['rol'] : 'usuario';

        $v  = new Validation();
        $ok = $v->check($data, array(
            'nombre'           => 'required|min:3|max:100',
            'email'            => 'required|email|max:150',
            'password'         => 'required|min:8|max:72|regex:/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d).+$/',
            'password_confirm' => 'required|same:password',
        ));

        if (!$ok) {
            Session::flash('errors', $v->errors());
            Session::flash('_old', $data);
            $this->redirect('registro');
        }

        if ((new Usuario())->porEmail($data['email'])) {
            Session::flash('error', 'Este correo ya está registrado. ¿Olvidaste tu contraseña?');
            Session::flash('_old', $data);
            $this->redirect('registro');
        }

        try {
            $token = bin2hex(random_bytes(32));
            $id    = (new Usuario())->crear(array(
                'nombre'   => $data['nombre'],
                'email'    => $data['email'],
                'password' => Auth::hashPassword($data['password']),
                'rol'      => $data['rol'],
            ), $token);

            $link = url('verificar/' . $token);
            Mailer::send(
                $data['email'],
                'Verifica tu cuenta en Artcania',
                self::tplVerificacion($data['nombre'], $link)
            );

            Logger::accion($id, 'REGISTRO', 'Nuevo usuario: ' . $data['email']);
            Session::flash('success', 'Cuenta creada. Revisa tu correo (' . $data['email'] . ') para verificarla. Revisa también spam.');
            $this->redirect('login');

        } catch (Throwable $e) {
            Logger::error('register: ' . $e->getMessage());
            Session::flash('error', 'No se pudo crear la cuenta. Intenta de nuevo.');
            Session::flash('_old', $data);
            $this->redirect('registro');
        }
    }

    // ── Verificar email ───────────────────────────────────────────────────────

    public function verify(string $token): void
    {
        if (empty($token) || strlen($token) !== 64) {
            Session::flash('error', 'Token inválido.');
            $this->redirect('login');
        }
        $ok = (new Usuario())->verificar($token);
        Session::flash(
            $ok ? 'success' : 'error',
            $ok ? '¡Correo verificado! Ya puedes iniciar sesión.'
                : 'El enlace de verificación es inválido o expiró (válido 24 h). Regístrate de nuevo o solicita reenvío.'
        );
        $this->redirect('login');
    }

    // ── Reenviar verificación ────────────────────────────────────────────────

    public function reenviarForm(): void
    {
        if (Auth::check()) $this->redirectByRole(Auth::rol());
        $this->view('auth/reenviar_verificacion', [], 'auth');
    }

    public function reenviar(): void
    {
        $this->csrfCheck();
        $email = strtolower(trim((string)Request::input('email')));

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            Session::flash('error', 'Ingresa un correo válido.');
            $this->redirect('reenviar-verificacion');
        }

        $u = (new Usuario())->porEmail($email);

        // No revelar si el email existe por seguridad
        if ($u && !$u['verificado'] && $u['activo']) {
            try {
                // Generar nuevo token si el anterior expiró
                $token = bin2hex(random_bytes(32));
                $stmt = Database::getInstance()->prepare(
                    'UPDATE usuarios SET token_verificacion = :token,
                     token_expira = DATE_ADD(NOW(), INTERVAL 24 HOUR)
                     WHERE email = :email AND (verificado = 0 OR verificado IS NULL) LIMIT 1'  
                                   );
                $stmt->execute([':token' => $token, ':email' => $email]);

                if ($stmt->rowCount() > 0) {
                    $link = url('verificar/' . $token);
                    Mailer::send(
                        $email,
                        'Verifica tu cuenta en Artcania',
                        self::tplVerificacion($u['nombre'], $link)
                    );
                    Logger::accion($u['id'], 'REENVIO_VERIFICACION', $email);
                }
            } catch (Throwable $e) {
                Logger::error('reenviar: ' . $e->getMessage());
            }
        }

        Session::flash('success', 'Si tu correo está registrado y pendiente de verificación, recibirás el enlace en breve. Revisa también spam.');
        $this->redirect('reenviar-verificacion');
    }

    // ── Recuperar contraseña ──────────────────────────────────────────────────

    public function forgot(): void
    {
        $this->csrfCheck();
        $email = strtolower(trim((string)Request::input('email')));

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            Session::flash('error', 'Ingresa un correo válido.');
            $this->redirect('recuperar');
        }

        try {
            $token = (new Usuario())->generarTokenReset($email);
            if ($token) {
                Mailer::send(
                    $email,
                    'Restablece tu contraseña – Artcania',
                    self::tplReset(url('reset/' . $token))
                );
            }
        } catch (Throwable $e) {
            Logger::error('forgot: ' . $e->getMessage());
        }

        // Mismo mensaje sin importar si el email existe (seguridad)
        Session::flash('success', 'Si el correo está registrado, recibirás un enlace en breve. Revisa también la carpeta de spam.');
        $this->redirect('recuperar');
    }

    // ── Reset contraseña ──────────────────────────────────────────────────────

    public function reset(): void
    {
        $this->csrfCheck();
        $token = trim((string)Request::input('token'));
        $pass  = (string)($_POST['password'] ?? '');
        $conf  = (string)($_POST['password_confirm'] ?? '');

        if (strlen($token) !== 64) {
            Session::flash('error', 'Token inválido.');
            $this->redirect('recuperar');
        }

        $v  = new Validation();
        $ok = $v->check(
            array('password' => $pass, 'password_confirm' => $conf),
            array(
                'password'         => 'required|min:8|max:72|regex:/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d).+$/',
                'password_confirm' => 'required|same:password',
            )
        );

        if (!$ok) {
            Session::flash('error', 'La contraseña debe tener mínimo 8 caracteres, una mayúscula, una minúscula y un número. Además deben coincidir.');
            $this->redirect('reset/' . $token);
        }

        try {
            $cambiado = (new Usuario())->resetPassword($token, Auth::hashPassword($pass));
            if ($cambiado) {
                Session::flash('success', 'Contraseña actualizada. Ya puedes iniciar sesión.');
                $this->redirect('login');
            } else {
                Session::flash('error', 'El enlace expiró o es inválido. Solicita uno nuevo.');
                $this->redirect('recuperar');
            }
        } catch (Throwable $e) {
            Logger::error('reset: ' . $e->getMessage());
            Session::flash('error', 'Error al cambiar contraseña. Intenta de nuevo.');
            $this->redirect('recuperar');
        }
    }

    // ── Logout ────────────────────────────────────────────────────────────────

    public function logout(): void
    {
        Auth::logout();
        Session::flash('success', 'Sesión cerrada.');
        $this->redirect('login');
    }

    // ── Google: paso 1 – redirigir a Google ──────────────────────────────────

    public function googleRedirect(): void
    {
        if (Auth::check()) $this->redirectByRole(Auth::rol());
        header('Location: ' . GoogleAuth::authUrl(), true, 302);
        exit;
    }

    // ── Google: paso 2 – callback ─────────────────────────────────────────────

    public function googleCallback(): void
    {
        $code  = trim($_GET['code']  ?? '');
        $state = trim($_GET['state'] ?? '');
        $error = trim($_GET['error'] ?? '');

        if ($error === 'access_denied') {
            Session::flash('error', 'Cancelaste el inicio de sesión con Google.');
            $this->redirect('login');
        }
        if (empty($code)) {
            Session::flash('error', 'Respuesta inválida de Google. Intenta de nuevo.');
            $this->redirect('login');
        }

        try {
            $perfil = GoogleAuth::handleCallback($code, $state);
        } catch (RuntimeException $e) {
            Logger::error('Google callback: ' . $e->getMessage());
            Session::flash('error', 'Error con Google: ' . $e->getMessage());
            $this->redirect('login');
        }

        $usuarioModel = new Usuario();
        $usuario = $usuarioModel->porEmail($perfil['email']);

        if ($usuario) {
            if (!$usuario['activo']) {
                Session::flash('error', 'Tu cuenta está desactivada. Contacta al administrador.');
                $this->redirect('login');
            }
            // Vincular google_id si no estaba
            $usuarioModel->actualizarGoogle((int)$usuario['id'], $perfil['id_google'], $perfil['avatar']);
        } else {
            // Crear cuenta nueva automáticamente
            try {
                $id = $usuarioModel->crearConGoogle(array(
                    'nombre'    => $perfil['nombre'],
                    'email'     => $perfil['email'],
                    'google_id' => $perfil['id_google'],
                    'avatar'    => $perfil['avatar'],
                    'rol'       => 'usuario',
                ));
                $usuario = $usuarioModel->porEmail($perfil['email']);
                Logger::accion($id, 'REGISTRO_GOOGLE', 'Cuenta creada vía Google');
            } catch (Throwable $e) {
                Logger::error('Google crear usuario: ' . $e->getMessage());
                Session::flash('error', 'No se pudo crear la cuenta. Intenta de nuevo.');
                $this->redirect('login');
            }
        }

        $_SESSION['user'] = array(
            'id'     => (int)$usuario['id'],
            'nombre' => $usuario['nombre'],
            'email'  => $usuario['email'],
            'rol'    => $usuario['rol'],
            'avatar' => $usuario['avatar'] ?? $perfil['avatar'],
        );
        session_regenerate_id(true);
        Logger::accion((int)$usuario['id'], 'LOGIN_GOOGLE', 'Login con Google');
        $this->redirectByRole($usuario['rol']);
    }

    // ── Helper de redirección por rol ─────────────────────────────────────────

    private function redirectByRole(?string $rol): void
    {
        switch ($rol) {
            case 'admin':   $this->redirect('admin/dashboard');   break;
            case 'artista': $this->redirect('artista/dashboard'); break;
            case 'curador': $this->redirect('curador/dashboard'); break;
            default:        $this->redirect('perfil');            break;
        }
    }

    // ── Plantillas de correo ──────────────────────────────────────────────────

    private static function tplVerificacion(string $nombre, string $link): string
    {
        $n = htmlspecialchars($nombre, ENT_QUOTES, 'UTF-8');
        return "
        <div style='font-family:Arial,sans-serif;max-width:600px;margin:0 auto;padding:20px;background:#f9f9f9'>
          <div style='background:#1a1a2e;padding:28px;text-align:center;border-radius:8px 8px 0 0'>
            <h1 style='color:#e8b86d;margin:0;font-size:26px'>🎨 Artcania</h1>
          </div>
          <div style='background:#fff;padding:30px;border-radius:0 0 8px 8px'>
            <h2 style='color:#333'>¡Bienvenido, {$n}!</h2>
            <p style='color:#555;line-height:1.6'>Gracias por registrarte. Haz clic para activar tu cuenta:</p>
            <div style='text-align:center;margin:28px 0'>
              <a href='{$link}' style='background:#e8b86d;color:#1a1a2e;padding:14px 32px;
                 text-decoration:none;border-radius:6px;font-weight:bold;font-size:16px;display:inline-block'>
                ✅ Verificar mi cuenta
              </a>
            </div>
            <p style='color:#888;font-size:13px'>Este enlace es válido por <strong>24 horas</strong>.</p>
            <p style='color:#aaa;font-size:12px'>Si el botón no funciona, copia este enlace:<br>
              <a href='{$link}' style='color:#e8b86d;word-break:break-all'>{$link}</a>
            </p>
          </div>
        </div>";
    }

    private static function tplReset(string $link): string
    {
        return "
        <div style='font-family:Arial,sans-serif;max-width:600px;margin:0 auto;padding:20px;background:#f9f9f9'>
          <div style='background:#1a1a2e;padding:28px;text-align:center;border-radius:8px 8px 0 0'>
            <h1 style='color:#e8b86d;margin:0;font-size:26px'>🎨 Artcania</h1>
          </div>
          <div style='background:#fff;padding:30px;border-radius:0 0 8px 8px'>
            <h2 style='color:#333'>Restablece tu contraseña</h2>
            <p style='color:#555;line-height:1.6'>Recibimos una solicitud para restablecer tu contraseña:</p>
            <div style='text-align:center;margin:28px 0'>
              <a href='{$link}' style='background:#e8b86d;color:#1a1a2e;padding:14px 32px;
                 text-decoration:none;border-radius:6px;font-weight:bold;font-size:16px;display:inline-block'>
                🔐 Restablecer contraseña
              </a>
            </div>
            <p style='color:#888;font-size:13px'>Válido por <strong>1 hora</strong>. Si no lo solicitaste, ignora este correo.</p>
            <p style='color:#aaa;font-size:12px'>
              <a href='{$link}' style='color:#e8b86d;word-break:break-all'>{$link}</a>
            </p>
          </div>
        </div>";
    }
}
