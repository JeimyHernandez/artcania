<?php
class Comentario extends Model {
    protected $table = 'comentarios';
    protected $timestamps = false;
    public function deObra(int $obraId) {
        $s=$this->db->prepare('SELECT c.*,u.nombre,u.avatar FROM comentarios c JOIN usuarios u ON u.id=c.usuario_id WHERE c.obra_id=:o AND c.estado="aprobado" ORDER BY c.creado_en');
        $s->bindValue(':o',$obraId);$s->execute();return $s->fetchAll();
    }
    public function pendientes() {
        $s=$this->db->query('SELECT c.*,u.nombre,o.titulo FROM comentarios c JOIN usuarios u ON u.id=c.usuario_id JOIN obras o ON o.id=c.obra_id WHERE c.estado="pendiente" ORDER BY c.creado_en');
        return $s->fetchAll();
    }
    public function crear(int $userId, int $obraId, string $texto) {
        return $this->insert(['usuario_id'=>$userId,'obra_id'=>$obraId,'contenido'=>$texto,'estado'=>'pendiente']);
    }
}