<?php $pageTitle = 'Gestión de Exposiciones'; ?>
<div class="admin-page-header mb-4">
  <h4 class="font-cinzel mb-0" style="color:var(--gold-light)">
    <i class="fa fa-landmark me-2"></i>Gestión de Exposiciones
  </h4>
</div>

<div class="card-magic p-4">
  <?php if(empty($exposiciones)): ?>
    <p style="color:var(--pearl-muted);font-size:.9rem"><i class="fa fa-circle-info me-2" style="color:var(--teal)"></i>No hay exposiciones registradas aún.</p>
  <?php else: ?>
  <div style="overflow-x:auto">
    <table class="table table-dark table-hover" style="font-size:.85rem;color:var(--pearl)">
      <thead>
        <tr style="border-bottom:1px solid var(--border);color:var(--gold-light)">
          <th>#</th>
          <th>Título</th>
          <th>Curador</th>
          <th>Inicio</th>
          <th>Fin</th>
          <th>Pública</th>
        </tr>
      </thead>  
      <tbody>
        <?php foreach($exposiciones as $ex): ?>
        <tr style="border-bottom:1px solid var(--border)">
          <td style="color:var(--pearl-muted)"><?= (int)$ex['id'] ?></td>
          <td><?= e($ex['titulo'] ?? '-') ?></td>
          <td style="color:var(--teal)"><?= e($ex['curador_nombre'] ?? 'Sin curador') ?></td>
          <td style="color:var(--pearl-muted)"><?= format_date($ex['fecha_inicio'] ?? '', 'd/m/Y') ?></td>
          <td style="color:var(--pearl-muted)"><?= $ex['fecha_fin'] ? format_date($ex['fecha_fin'], 'd/m/Y') : '—' ?></td>
          <td>
            <?php if(!empty($ex['publica'])): ?>
              <span style="background:rgba(52,211,153,.15);color:#34d399;padding:2px 8px;border-radius:20px;font-size:.75rem">Pública</span>
            <?php else: ?>
              <span style="background:rgba(248,113,113,.15);color:#f87171;padding:2px 8px;border-radius:20px;font-size:.75rem">Privada</span>
            <?php endif; ?>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <p style="color:var(--pearl-muted);font-size:.78rem;margin-top:.5rem">Total: <?= count($exposiciones) ?> exposición(es)</p>
  <?php endif; ?>
</div>
