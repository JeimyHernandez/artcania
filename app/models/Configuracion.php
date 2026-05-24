<?php
class Configuracion extends Model {
    protected $table = 'configuracion';

    public function get(string $clave, $default = null) {
        $s = $this->db->prepare('SELECT valor, tipo FROM configuracion WHERE clave = :c LIMIT 1');
        $s->bindValue(':c', $clave);
        $s->execute();
        $r = $s->fetch();
        if (!$r) return $default;

        if ($r['tipo'] === 'int')  return (int)$r['valor'];
        if ($r['tipo'] === 'bool') return (bool)$r['valor'];
        if ($r['tipo'] === 'json') return json_decode($r['valor'], true);
        return $r['valor'];
    }

    public function set(string $clave, $valor) {
        $s = $this->db->prepare('UPDATE configuracion SET valor = :v WHERE clave = :c');
        $s->bindValue(':v', (string)$valor);
        $s->bindValue(':c', $clave);
        $s->execute();
    }
}