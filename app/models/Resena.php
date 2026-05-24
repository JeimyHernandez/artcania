<?php
class Resena extends Model {
    protected $table = 'resenas';
    public function deArtista(int $artistaId) {
        $s=$this->db->prepare('SELECT r.*,u.nombre,u.avatar FROM resenas r JOIN usuarios u ON u.id=r.usuario_id WHERE r.artista_id=:a AND r.aprobada=1 ORDER BY r.creado_en DESC');
        $s->bindValue(':a',$artistaId); $s->execute(); return $s->fetchAll();
    }
}