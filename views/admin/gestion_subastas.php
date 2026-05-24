<?php $pageTitle = 'Gestión de Subastas'; ?>
<?php if(!isset($subastas)) $subastas = []; ?>
<div class="d-flex justify-content-between align-items-center mb-4">
  <h2 class="fw-bold"><i class="fa fa-gavel me-2"></i>Gestión de Subastas</h2>
</div>
<div class="card shadow">
  <div class="card-body p-0 table-responsive">
    <table class="table table-hover mb-0 tabla-dt">
      <thead class="table-dark"><tr>
        <th>#</th><th>Obra ID</th><th>Precio Inicial</th><th>Precio Actual</th><th>Fecha Fin</th><th>Estado</th><th>Pujas</th>
      </tr></thead>
      <tbody>
        <?php foreach($subastas as $s): ?>
        <?php $stmt = Database::getInstance()->prepare('SELECT COUNT(*) FROM pujas WHERE subasta_id=?'); $stmt->execute([$s['id']]); $npujas = (int)$stmt->fetchColumn(); ?>
        <tr>
          <td><?= $s['id'] ?></td>
          <td><?= $s['obra_id'] ?></td>
          <td><?= money($s['precio_inicial']) ?></td>
          <td class="fw-bold text-success"><?= money($s['precio_actual']) ?></td>
          <td><?= format_date($s['fecha_fin']) ?></td>
          <td><span class="badge bg-<?= $s['estado']==='activa'?'success':($s['estado']==='programada'?'warning':'secondary') ?>"><?= e($s['estado']) ?></span></td>
          <td><?= $npujas ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
