<?php
class Certificado extends Model {
    protected $table = 'certificados';
    public function deUsuario(int $userId) {
        $s=$this->db->prepare('SELECT c.*,o.titulo,u.nombre as artista FROM certificados c JOIN obras o ON o.id=c.obra_id JOIN usuarios u ON u.id=o.artista_id WHERE c.usuario_id=:id');
        $s->bindValue(':id',$userId); $s->execute(); return $s->fetchAll();
    }
    public function verificar(string $codigo) {
        $s=$this->db->prepare('SELECT c.*,o.titulo,u.nombre as propietario,ua.nombre as artista FROM certificados c JOIN obras o ON o.id=c.obra_id JOIN usuarios u ON u.id=c.usuario_id JOIN usuarios ua ON ua.id=o.artista_id WHERE c.codigo=:c');
        $s->bindValue(':c',$codigo); $s->execute(); return $s->fetch()?:null;
    }
}