<?php
class FanArt extends Model {
    protected $table = 'fanarts';
    public function aprobados(int $limit=20) {
        $s=$this->db->prepare('SELECT f.*,u.nombre as autor FROM fanarts f JOIN usuarios u ON u.id=f.usuario_id WHERE f.aprobado=1 ORDER BY f.creado_en DESC LIMIT :l');
        $s->bindValue(':l',$limit,PDO::PARAM_INT); $s->execute(); return $s->fetchAll();
    }
    public function all() {
        $s=$this->db->query('SELECT f.*,u.nombre as autor FROM fanarts f JOIN usuarios u ON u.id=f.usuario_id ORDER BY f.creado_en DESC LIMIT 100');
        return $s->fetchAll();
    }
}