<?php
class MensajeChat extends Model {
    protected $table = 'mensajes_chat';
    public function deConversacion(int $convId, int $limit=50) {
        $s=$this->db->prepare('SELECT m.*,u.nombre as remitente_nombre FROM mensajes_chat m JOIN usuarios u ON u.id=m.remitente_id WHERE m.conversacion_id=:c ORDER BY m.creado_en ASC LIMIT :l');
        $s->bindValue(':c',$convId);$s->bindValue(':l',$limit,PDO::PARAM_INT);$s->execute();return $s->fetchAll();
    }
    public function enviar(int $convId, int $userId, string $msg) {
        return $this->insert(['conversacion_id'=>$convId,'remitente_id'=>$userId,'mensaje'=>$msg]);
    }
    public function marcarLeidos(int $convId, int $userId) {
        $s=$this->db->prepare('UPDATE mensajes_chat SET leido=1 WHERE conversacion_id=:c AND remitente_id!=:u AND leido=0');
        $s->bindValue(':c',$convId);$s->bindValue(':u',$userId);$s->execute();
    }
}