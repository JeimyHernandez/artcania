<?php
class Logger {

    public static function accion($userId, $accion, $detalle, $ip = '') {
        try {
            $db  = Database::getInstance();
            $ip  = $ip ? $ip : (isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '');
            $ua  = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
            $uid = $userId ? (int)$userId : null;

            $stmt = $db->prepare(
                'INSERT INTO bitacora (usuario_id, accion, detalle, ip, agente_usuario)
                 VALUES (:u, :a, :d, :ip, :ua)'
            );
            $stmt->bindValue(':u',  $uid,    PDO::PARAM_INT);
            $stmt->bindValue(':a',  $accion);
            $stmt->bindValue(':d',  $detalle);
            $stmt->bindValue(':ip', $ip);
            $stmt->bindValue(':ua', $ua);
            $stmt->execute();
        } catch (Exception $e) {
            self::file('errores', '[Logger::accion] ' . $e->getMessage());
        } catch (Throwable $e) {
            self::file('errores', '[Logger::accion] ' . $e->getMessage());
        }
        self::file('acceso', "[{$accion}] user=" . ($userId ? $userId : 'guest') . " {$detalle}");
    }

    public static function error($msg) {
        self::file('errores', '[ERROR] ' . $msg);
    }

    public static function info($msg) {
        self::file('acceso', '[INFO] ' . $msg);
    }

    public static function chat($msg) {
        self::file('chat', '[CHAT] ' . $msg);
    }

    public static function admin($msg) {
        self::file('acciones_admin', '[ADMIN] ' . $msg);
    }

    private static function file($log, $msg) {
        $dir = BASE_PATH . '/logs/';
        if (!is_dir($dir)) {
            @mkdir($dir, 0755, true);
        }
        $line = '[' . date('Y-m-d H:i:s') . '] ' . $msg . PHP_EOL;
        @file_put_contents($dir . $log . '.log', $line, FILE_APPEND | LOCK_EX);
    }
}
