<?php
class Notificacion extends Model {
    protected $table = 'notificaciones';
    public function deUsuario(int $userId, $soloNoLeidas=false) {
        $sql='SELECT * FROM notificaciones WHERE usuario_id=:u';
        if($soloNoLeidas) $sql.=' AND leida=0';
        $sql.=' ORDER BY creado_en DESC LIMIT 20';
        $s=$this->db->prepare($sql); $s->bindValue(':u',$userId); $s->execute(); return $s->fetchAll();
    }
    public function noLeidas(int $userId) {
        $s=$this->db->prepare('SELECT COUNT(*) FROM notificaciones WHERE usuario_id=:u AND leida=0');
        $s->bindValue(':u',$userId); $s->execute(); return (int)$s->fetchColumn();
    }
    public function marcarLeida(int $id) {
        $this->db->prepare('UPDATE notificaciones SET leida=1 WHERE id=:id')->execute([':id'=>$id]);
    }
    public function marcarTodasLeidas(int $userId) {
        $this->db->prepare('UPDATE notificaciones SET leida=1 WHERE usuario_id=:u AND leida=0')->execute([':u'=>$userId]);
    }
    public function crear(int $userId, string $tipo, string $mensaje, $refId=null) {
        return $this->insert(['usuario_id'=>$userId,'tipo'=>$tipo,'mensaje'=>$mensaje,'referencia_id'=>$refId]);
    }
}