<?php
class InscripcionTaller extends Model {
    protected $table = 'inscripciones_taller';
    public function deUsuario(int $userId) {
        $s=$this->db->prepare('SELECT i.*,t.titulo,t.fecha_inicio,t.fecha_fin,u.nombre as instructor FROM inscripciones_taller i JOIN talleres t ON t.id=i.taller_id JOIN usuarios u ON u.id=t.instructor_id WHERE i.usuario_id=:id');
        $s->bindValue(':id',$userId); $s->execute(); return $s->fetchAll();
    }
}