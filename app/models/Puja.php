<?php
class Puja extends Model {
    protected $timestamps = false;
    protected $table = 'pujas';

    public function deSubasta(int $subastaId) {
        $s = $this->db->prepare(
            'SELECT p.*, u.nombre FROM pujas p
             JOIN usuarios u ON u.id = p.usuario_id
             WHERE p.subasta_id = :s
             ORDER BY p.monto DESC'
        );
        $s->bindValue(':s', $subastaId, PDO::PARAM_INT);
        $s->execute();
        return $s->fetchAll();
    }

    public function deUsuario(int $userId) {
        $s = $this->db->prepare(
            'SELECT p.*, sub.estado AS sub_estado, o.titulo
             FROM pujas p
             JOIN subastas sub ON sub.id = p.subasta_id
             JOIN obras o ON o.id = sub.obra_id
             WHERE p.usuario_id = :u
             ORDER BY p.creado_en DESC'
        );
        $s->bindValue(':u', $userId, PDO::PARAM_INT);
        $s->execute();
        return $s->fetchAll();
    }
}