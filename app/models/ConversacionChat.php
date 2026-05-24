<?php
class ConversacionChat extends Model {
    protected $table = 'conversaciones_chat';
    public function deUsuario(int $userId) {
        $s=$this->db->prepare('SELECT c.*,u.nombre as otro_nombre,u.avatar as otro_avatar,(SELECT COUNT(*) FROM mensajes_chat WHERE conversacion_id=c.id AND leido=0 AND remitente_id!=:u2) as no_leidos FROM conversaciones_chat c JOIN usuarios u ON u.id=IF(c.usuario1_id=:u1,c.usuario2_id,c.usuario1_id) WHERE c.usuario1_id=:u3 OR c.usuario2_id=:u4 ORDER BY c.actualizado_en DESC');
        $s->bindValue(':u1',$userId);$s->bindValue(':u2',$userId);$s->bindValue(':u3',$userId);$s->bindValue(':u4',$userId);$s->execute();return $s->fetchAll();
    }
    public function entre(int $u1, int $u2) {
        $s=$this->db->prepare('SELECT * FROM conversaciones_chat WHERE (usuario1_id=:a AND usuario2_id=:b) OR (usuario1_id=:c AND usuario2_id=:d) LIMIT 1');
        $s->bindValue(':a',$u1);$s->bindValue(':b',$u2);$s->bindValue(':c',$u2);$s->bindValue(':d',$u1);$s->execute();return $s->fetch()?:null;
    }
    public function crear(int $u1, int $u2) { return $this->insert(['usuario1_id'=>$u1,'usuario2_id'=>$u2]); }
}