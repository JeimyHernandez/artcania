<?php
class Evento extends Model {
    protected $table = 'eventos';
    public function proximos(int $limit=10) {
        $s=$this->db->prepare('SELECT e.*,u.nombre as organizador FROM eventos e LEFT JOIN usuarios u ON u.id=e.organizador_id WHERE e.activo=1 AND e.fecha_inicio>=NOW() ORDER BY e.fecha_inicio LIMIT :l');
        $s->bindValue(':l',$limit,PDO::PARAM_INT); $s->execute(); return $s->fetchAll();
    }
}