<?php
class Video extends Model {
    protected $table = 'videos';
    public function all() {
        $s=$this->db->query('SELECT v.*,u.nombre as artista FROM videos v JOIN usuarios u ON u.id=v.artista_id WHERE v.estado="aprobado" ORDER BY v.creado_en DESC LIMIT 50');
        return $s->fetchAll();
    }
    public function porCategoria(string $cat) {
        $s=$this->db->prepare('SELECT v.*,u.nombre as artista FROM videos v JOIN usuarios u ON u.id=v.artista_id WHERE v.categoria=:c AND v.estado="aprobado"');
        $s->bindValue(':c',$cat); $s->execute(); return $s->fetchAll();
    }
}