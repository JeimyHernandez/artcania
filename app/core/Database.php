<?php
class Database {

    private static $pdo = null;

    public static function getInstance() {
        if (self::$pdo === null) {
            $cfg = artcania_config();
            $db  = $cfg['database'];
            $dsn = "mysql:host={$db['host']};port={$db['port']};dbname={$db['database']};charset={$db['charset']}";
            try {
                self::$pdo = new PDO($dsn, $db['username'], $db['password'], $db['options']);
            } catch (PDOException $e) {
                error_log('Artcania DB Error: ' . $e->getMessage());
                die(
                    '<div style="font-family:sans-serif;padding:40px;text-align:center">' .
                    '<h2 style="color:#e74c3c">&#9888; Error de conexión a la base de datos</h2>' .
                    '<p>Verifica que MySQL está corriendo y que los datos en <code>config/database.php</code> son correctos.</p>' .
                    '<p style="color:#888;font-size:.9em">Host: <b>' . htmlspecialchars($db['host']) . '</b> &nbsp;|&nbsp; BD: <b>' . htmlspecialchars($db['database']) . '</b></p>' .
                    '</div>'
                );
            }
        }
        return self::$pdo;
    }

    public static function reset() {
        self::$pdo = null;
    }
}
