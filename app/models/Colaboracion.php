<?php
class Colaboracion extends Model {
    protected $table = 'colaboraciones';
    public function deArtista(int $artistaId) {
        $s=$this->db->prepare('SELECT c.*,u1.nombre as artista1,u2.nombre as artista2 FROM colaboraciones c JOIN usuarios u1 ON u1.id=c.artista1_id JOIN usuarios u2 ON u2.id=c.artista2_id WHERE c.artista1_id=:a OR c.artista2_id=:b ORDER BY c.creado_en DESC');
        $s->bindValue(':a',$artistaId); $s->bindValue(':b',$artistaId); $s->execute(); return $s->fetchAll();
    }
}