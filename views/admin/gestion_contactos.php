<?php $pageTitle = 'Revisión de Contactos'; ?>
<?php
$contactos = Database::getInstance()->query(
  'SELECT c.*,u.nombre as remitente,ua.nombre as artista_nombre FROM contactos_artista c JOIN usuarios u ON u.id=c.usuario_id JOIN usuarios ua ON ua.id=c.artista_id ORDER BY c.creado_en DESC LIMIT 100'
)->fetchAll();
?>
<h2 class="fw-bold mb-4"><i class="fa fa-envelope me-2"></i>Contactos a Artistas</h2>
<div class="card shadow">
  <div class="card-body p-0 table-responsive">
    <table class="table table-hover mb-0 tabla-dt">
      <thead class="table-dark"><tr><th>#</th><th>Remitente</th><th>Artista</th><th>Asunto</th><th>Leído</th><th>Fecha</th></tr></thead>
      <tbody>
        <?php foreach($contactos as $c): ?>
        <tr>
          <td><?= $c['id'] ?></td>
          <td><?= e($c['remitente']) ?></td>
          <td><?= e($c['artista_nombre']) ?></td>
          <td><?= e(truncate($c['asunto'],40)) ?></td>
          <td><?= $c['leido']?'<i class="fa fa-check text-success"></i>':'<i class="fa fa-times text-danger"></i>' ?></td>
          <td class="small"><?= format_date($c['creado_en']) ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
