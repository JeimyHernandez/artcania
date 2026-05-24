<?php
class Galeria extends Model {
    protected $table = 'galerias';
    public function conObras(int $id) {
        $g=$this->find($id); if(!$g) return null;
        $s=$this->db->prepare('SELECT o.*,u.nombre as artista FROM galeria_obras go2 JOIN obras o ON o.id=go2.obra_id JOIN usuarios u ON u.id=o.artista_id WHERE go2.galeria_id=:id ORDER BY go2.orden');
        $s->bindValue(':id',$id); $s->execute(); $g['obras']=$s->fetchAll();
        return $g;
    }
}