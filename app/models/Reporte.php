<?php
class Reporte extends Model {
    protected $table = 'reportes_contenido';
    public function pendientes() {
        $s=$this->db->query('SELECT r.*,u.nombre as reportador FROM reportes_contenido r JOIN usuarios u ON u.id=r.usuario_id WHERE r.estado="pendiente" ORDER BY r.creado_en DESC');
        return $s->fetchAll();
    }
    public function reportar(int $userId, string $tipo, int $contenidoId, string $motivo) {
        return $this->insert(['usuario_id'=>$userId,'tipo_contenido'=>$tipo,'contenido_id'=>$contenidoId,'motivo'=>$motivo]);
    }
}