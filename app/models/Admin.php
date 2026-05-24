<?php
class Admin extends Model {
    protected $table = 'usuarios';
    public function admins() {
        $s=$this->db->query("SELECT * FROM usuarios WHERE rol='admin' ORDER BY nombre");
        return $s->fetchAll();
    }
    public function estadisticasCompletas() {
        $db=$this->db;
        return [
            'usuarios'        => (int)$db->query('SELECT COUNT(*) FROM usuarios')->fetchColumn(),
            'artistas'        => (int)$db->query("SELECT COUNT(*) FROM usuarios WHERE rol='artista'")->fetchColumn(),
            'curadores'       => (int)$db->query("SELECT COUNT(*) FROM usuarios WHERE rol='curador'")->fetchColumn(),
            'obras_total'     => (int)$db->query('SELECT COUNT(*) FROM obras')->fetchColumn(),
            'obras_aprobadas' => (int)$db->query("SELECT COUNT(*) FROM obras WHERE estado='aprobada'")->fetchColumn(),
            'obras_pendientes'=> (int)$db->query("SELECT COUNT(*) FROM obras WHERE estado='pendiente'")->fetchColumn(),
            'ventas'          => (int)$db->query("SELECT COUNT(*) FROM ventas WHERE estado='completada'")->fetchColumn(),
            'ingresos'        => (float)$db->query("SELECT COALESCE(SUM(monto_total),0) FROM ventas WHERE estado='completada'")->fetchColumn(),
            'subastas_activas'=> (int)$db->query("SELECT COUNT(*) FROM subastas WHERE estado='activa'")->fetchColumn(),
            'comentarios'     => (int)$db->query('SELECT COUNT(*) FROM comentarios')->fetchColumn(),
        ];
    }
}