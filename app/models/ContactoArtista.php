<?php
class ContactoArtista extends Model {
    protected $table = 'contactos_artista';
    public function enviar(int $artistaId, int $userId, string $asunto, string $mensaje) {
        return $this->insert(['artista_id'=>$artistaId,'usuario_id'=>$userId,'asunto'=>$asunto,'mensaje'=>$mensaje]);
    }
    public function recibidos(int $artistaId) {
        $s=$this->db->prepare('SELECT c.*,u.nombre as remitente FROM contactos_artista c JOIN usuarios u ON u.id=c.usuario_id WHERE c.artista_id=:a ORDER BY c.creado_en DESC');
        $s->bindValue(':a',$artistaId);$s->execute();return $s->fetchAll();
    }
}