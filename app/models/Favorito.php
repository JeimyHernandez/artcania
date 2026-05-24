<?php
class Favorito extends Model {
    protected $timestamps = false;
    protected $table = 'favoritos';

    public function deUsuario(int $userId) {
        $s = $this->db->prepare(
            'SELECT f.*, o.titulo, o.imagen_principal, o.precio, u.nombre AS artista
             FROM favoritos f
             JOIN obras o ON o.id = f.obra_id
             JOIN usuarios u ON u.id = o.artista_id
             WHERE f.usuario_id = :u
             ORDER BY f.creado_en DESC'
        );
        $s->bindValue(':u', $userId, PDO::PARAM_INT);
        $s->execute();
        return $s->fetchAll();
    }

    public function toggle(int $userId, int $obraId) {
        $s = $this->db->prepare(
            'SELECT id FROM favoritos WHERE usuario_id = :u AND obra_id = :o LIMIT 1'
        );
        $s->bindValue(':u', $userId, PDO::PARAM_INT);
        $s->bindValue(':o', $obraId, PDO::PARAM_INT);
        $s->execute();
        $exists = $s->fetch();

        if ($exists) {
            $this->delete($exists['id']);
            return false; // removed
        }
        $this->insert(['usuario_id' => $userId, 'obra_id' => $obraId]);
        return true; // added
    }
}