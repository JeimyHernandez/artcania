<?php $pageTitle = 'Moderación de Chat'; ?>
<?php
$msgs = Database::getInstance()->query(
  'SELECT m.*,u.nombre as remitente,c.usuario1_id,c.usuario2_id FROM mensajes_chat m JOIN usuarios u ON u.id=m.remitente_id JOIN conversaciones_chat c ON c.id=m.conversacion_id ORDER BY m.creado_en DESC LIMIT 100'
)->fetchAll();
?>
<h2 class="fw-bold mb-4"><i class="fa fa-comments me-2"></i>Moderación de Chat</h2>
<div class="card shadow">
  <div class="card-body p-0 table-responsive">
    <table class="table table-sm table-hover mb-0 tabla-dt">
      <thead class="table-dark"><tr><th>#</th><th>Remitente</th><th>Conversación</th><th>Mensaje</th><th>Estado</th><th>Fecha</th></tr></thead>
      <tbody>
        <?php foreach($msgs as $m): ?>
        <tr class="<?= $m['eliminado']?'table-danger':'' ?>">
          <td><?= $m['id'] ?></td>
          <td><?= e($m['remitente']) ?></td>
          <td>Conv #<?= $m['conversacion_id'] ?></td>
          <td class="small"><?= e(truncate($m['mensaje'],60)) ?></td>
          <td><span class="badge bg-<?= $m['eliminado']?'danger':($m['leido']?'success':'secondary') ?>"><?= $m['eliminado']?'Eliminado':($m['leido']?'Leído':'Enviado') ?></span></td>
          <td class="small"><?= format_date($m['creado_en']) ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
