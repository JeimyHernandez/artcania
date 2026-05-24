<?php
class Seguimiento extends Model {
    protected $timestamps = false;
    protected $table = 'seguimientos';

    public function toggle(int $seguidorId, int $artistaId) {
        $s = $this->db->prepare(
            'SELECT id FROM seguimientos WHERE seguidor_id = :s AND artista_id = :a LIMIT 1'
        );
        $s->bindValue(':s', $seguidorId, PDO::PARAM_INT);
        $s->bindValue(':a', $artistaId, PDO::PARAM_INT);
        $s->execute();
        $e = $s->fetch();

        if ($e) {
            $this->delete($e['id']);
            return false;
        }
        $this->insert(['seguidor_id' => $seguidorId, 'artista_id' => $artistaId]);
        return true;
    }

    public function sigue(int $seguidorId, int $artistaId) {
        $s = $this->db->prepare(
            'SELECT COUNT(*) FROM seguimientos WHERE seguidor_id = :s AND artista_id = :a'
        );
        $s->bindValue(':s', $seguidorId, PDO::PARAM_INT);
        $s->bindValue(':a', $artistaId, PDO::PARAM_INT);
        $s->execute();
        return (bool)$s->fetchColumn();
    }
}