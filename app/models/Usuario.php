<?php
/**
 * Usuario.php – PHP 7.4
 * Modelo con: Google Login, 3 intentos/1h bloqueo, SQL seguro (prepared statements).
 */
class Usuario extends Model
{
    protected $table = 'usuarios';

    // ── Consultas básicas ────────────────────────────────────────────────────

    public function porEmail(string $email): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT id, nombre, email, password, rol, avatar, verificado, activo,
                    intentos_fallidos, bloqueado_hasta, google_id
             FROM usuarios WHERE email = :email LIMIT 1'
        );
        $stmt->bindValue(':email', strtolower(trim($email)), PDO::PARAM_STR);
        $stmt->execute();
        $r = $stmt->fetch(PDO::FETCH_ASSOC);
        return $r ?: null;
    }

    public function porGoogleId(string $googleId): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT id, nombre, email, rol, avatar, verificado, activo
             FROM usuarios WHERE google_id = :gid LIMIT 1'
        );
        $stmt->bindValue(':gid', $googleId, PDO::PARAM_STR);
        $stmt->execute();
        $r = $stmt->fetch(PDO::FETCH_ASSOC);
        return $r ?: null;
    }

    // ── Registro ─────────────────────────────────────────────────────────────

    public function crear(array $data, string $token): int
    {
        $stmt = $this->db->prepare(
            'CALL sp_registrar_usuario(:nombre, :email, :pass, :rol, :token)'
        );
        $stmt->bindValue(':nombre', $data['nombre'],   PDO::PARAM_STR);
        $stmt->bindValue(':email',  strtolower(trim($data['email'])), PDO::PARAM_STR);
        $stmt->bindValue(':pass',   $data['password'], PDO::PARAM_STR);
        $stmt->bindValue(':rol',    $data['rol'] ?? 'usuario', PDO::PARAM_STR);
        $stmt->bindValue(':token',  $token, PDO::PARAM_STR);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? (int)$row['id_usuario'] : 0;
    }

    public function crearConGoogle(array $data): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO usuarios (nombre, email, google_id, avatar, rol, verificado, activo, creado_en)
             VALUES (:nombre, :email, :gid, :avatar, :rol, 1, 1, NOW())'
        );
        $stmt->bindValue(':nombre', $data['nombre'],    PDO::PARAM_STR);
        $stmt->bindValue(':email',  $data['email'],     PDO::PARAM_STR);
        $stmt->bindValue(':gid',    $data['google_id'], PDO::PARAM_STR);
        $stmt->bindValue(':avatar', $data['avatar'],    PDO::PARAM_STR);
        $stmt->bindValue(':rol',    $data['rol'],       PDO::PARAM_STR);
        $stmt->execute();
        return (int)$this->db->lastInsertId();
    }

    public function actualizarGoogle(int $id, string $googleId, string $avatar): void
    {
        $stmt = $this->db->prepare(
            'UPDATE usuarios SET google_id = :gid, avatar = :avatar
             WHERE id = :id AND (google_id IS NULL OR google_id = "") LIMIT 1'
        );
        $stmt->bindValue(':gid',    $googleId, PDO::PARAM_STR);
        $stmt->bindValue(':avatar', $avatar,   PDO::PARAM_STR);
        $stmt->bindValue(':id',     $id,       PDO::PARAM_INT);
        $stmt->execute();
    }

    // ── Verificación email ───────────────────────────────────────────────────

    public function verificar(string $token): bool
    {
        $stmt = $this->db->prepare(
            'UPDATE usuarios
             SET verificado = 1, token_verificacion = NULL, token_expira = NULL
             WHERE token_verificacion = :token AND token_expira > NOW() AND verificado = 0 LIMIT 1'
        );
        $stmt->bindValue(':token', $token, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    // ── Reset contraseña ─────────────────────────────────────────────────────

    public function generarTokenReset(string $email): ?string
    {
        $token = bin2hex(random_bytes(32));
        $stmt  = $this->db->prepare(
            'UPDATE usuarios SET token_reset = :token,
             reset_expira = DATE_ADD(NOW(), INTERVAL 1 HOUR)
             WHERE email = :email AND activo = 1 LIMIT 1'
        );
        $stmt->bindValue(':token', $token, PDO::PARAM_STR);
        $stmt->bindValue(':email', strtolower(trim($email)), PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->rowCount() > 0 ? $token : null;
    }

    public function verificarTokenReset(string $token): bool
    {
        $stmt = $this->db->prepare(
            'SELECT id FROM usuarios WHERE token_reset = :token AND reset_expira > NOW() LIMIT 1'
        );
        $stmt->bindValue(':token', $token, PDO::PARAM_STR);
        $stmt->execute();
        return (bool)$stmt->fetch();
    }

    public function resetPassword(string $token, string $hash): bool
    {
        $stmt = $this->db->prepare(
            'UPDATE usuarios SET password = :hash, token_reset = NULL, reset_expira = NULL,
             intentos_fallidos = 0, bloqueado_hasta = NULL
             WHERE token_reset = :token AND reset_expira > NOW() LIMIT 1'
        );
        $stmt->bindValue(':hash',  $hash,  PDO::PARAM_STR);
        $stmt->bindValue(':token', $token, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    // ── Intentos de login ────────────────────────────────────────────────────

    public function resetIntentos(int $id): void
    {
        $this->db->prepare(
            'UPDATE usuarios SET intentos_fallidos = 0, bloqueado_hasta = NULL WHERE id = :id LIMIT 1'
        )->execute(array(':id' => $id));
    }

    public function incrementarIntentos(int $id, int $max = 3): int
    {
        $cfg   = artcania_config();
        $horas = (int)($cfg['security']['login_block_hours'] ?? 1);

        $this->db->prepare(
            'UPDATE usuarios SET intentos_fallidos = intentos_fallidos + 1 WHERE id = :id LIMIT 1'
        )->execute(array(':id' => $id));

        $stmt = $this->db->prepare('SELECT intentos_fallidos FROM usuarios WHERE id = :id LIMIT 1');
        $stmt->execute(array(':id' => $id));
        $intentos = (int)$stmt->fetchColumn();

        if ($intentos >= $max) {
            $this->db->prepare(
                'UPDATE usuarios SET bloqueado_hasta = DATE_ADD(NOW(), INTERVAL :h HOUR) WHERE id = :id LIMIT 1'
            )->execute(array(':h' => $horas, ':id' => $id));

            $this->log($id, 'CUENTA_BLOQUEADA', "Bloqueada {$horas}h por {$intentos} intentos");
        } else {
            $this->log($id, 'LOGIN_FALLIDO', "Intento #{$intentos} de {$max}");
        }

        return $intentos;
    }

    private function log(int $uid, string $accion, string $detalle): void
    {
        try {
            $ip = filter_var($_SERVER['REMOTE_ADDR'] ?? '', FILTER_VALIDATE_IP) ?: '';
            $this->db->prepare(
                'INSERT INTO bitacora (usuario_id, accion, detalle, ip) VALUES (:uid, :a, :d, :ip)'
            )->execute(array(':uid' => $uid, ':a' => $accion, ':d' => $detalle, ':ip' => $ip));
        } catch (Throwable $e) {
            error_log('Bitacora: ' . $e->getMessage());
        }
    }
    public function contarUsuarios(): int {
        $r = $this->db->query("SELECT COUNT(*) FROM usuarios WHERE activo=1")->fetchColumn();
        return (int)$r;
    }
}
