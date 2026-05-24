<?php
class Curador extends Model {
    protected $table = 'curadores';
    public function conUsuario(int $userId) {
        $s=$this->db->prepare('SELECT c.*,u.nombre,u.email FROM curadores c JOIN usuarios u ON u.id=c.usuario_id WHERE c.usuario_id=:id');
        $s->bindValue(':id',$userId); $s->execute(); return $s->fetch()?:null;
    }
}