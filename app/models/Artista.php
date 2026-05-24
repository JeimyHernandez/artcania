<?php
class Artista extends Model {
    protected $table = 'artistas';
    public function conUsuario(int $userId) {
        $s=$this->db->prepare('SELECT a.*,u.nombre,u.email,u.avatar FROM artistas a JOIN usuarios u ON u.id=a.usuario_id WHERE a.usuario_id=:id');
        $s->bindValue(':id',$userId);$s->execute();return $s->fetch()?:null;
    }
    public function destacados(int $limit=8) {
        $s=$this->db->prepare('SELECT a.*,u.nombre,u.avatar FROM artistas a JOIN usuarios u ON u.id=a.usuario_id WHERE a.destacado=1 ORDER BY a.puntuacion DESC LIMIT :l');
        $s->bindValue(':l',$limit,PDO::PARAM_INT);$s->execute();return $s->fetchAll();
    }
    public function todos() {
        $s=$this->db->query('SELECT a.*,u.nombre,u.email,u.avatar FROM artistas a JOIN usuarios u ON u.id=a.usuario_id ORDER BY u.nombre');
        return $s->fetchAll();
    }
    public function crearPerfil(int $userId, array $d) {
        $d['usuario_id']=$userId; return $this->insert($d);
    }
    public function contarActivos(): int {
        $r = $this->db->query("SELECT COUNT(*) FROM artistas")->fetchColumn();
        return (int)$r;
    }
}
