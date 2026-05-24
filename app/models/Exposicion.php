<?php
class Exposicion extends Model {
    protected $table = 'exposiciones';
    public function activas() {
        $s = $this->db->prepare("SELECT e.*,u.nombre as curador_nombre FROM exposiciones e LEFT JOIN usuarios u ON u.id=e.curador_id WHERE e.publica=1 AND (e.fecha_fin IS NULL OR e.fecha_fin>=CURDATE()) ORDER BY e.fecha_inicio ASC");
        $s->execute(); return $s->fetchAll();
    }
}