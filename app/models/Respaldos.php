<?php
class Respaldos extends Model {
    protected $table = 'respaldos';
    public function all() {
        $s=$this->db->query('SELECT r.*,u.nombre as creado_por_nombre FROM respaldos r LEFT JOIN usuarios u ON u.id=r.creado_por ORDER BY r.creado_en DESC LIMIT 50');
        return $s->fetchAll();
    }
}