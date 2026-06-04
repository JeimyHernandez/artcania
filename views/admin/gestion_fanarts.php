<?php $pageTitle = 'Gestión de FanArts'; ?>
<div class="admin-page-header mb-4">
  <h4 class="font-cinzel mb-0" style="color:var(--gold-light)">
    <i class="fa fa-star me-2"></i>Gestión de FanArts
  </h4>
</div>

<div class="card-magic p-4">
  <?php if(empty($fanarts)): ?>
    <p style="color:var(--pearl-muted);font-size:.9rem"><i class="fa fa-circle-info me-2" style="color:var(--teal)"></i>No hay fanarts registrados aún.</p>
  <?php else: ?>
  <div style="overflow-x:auto">
    <table class="table table-dark table-hover" style="font-size:.85rem;color:var(--pearl)">
      <thead>
        <tr style="border-bottom:1px solid var(--border);color:var(--gold-light)">
          <th>#</th>
          <th>Imagen</th>
          <th>Título</th>
          <th>Autor</th>
          <th>Aprobado</th>
          <th>Fecha</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($fanarts as $f): ?>
        <tr style="border-bottom:1px solid var(--border)">
          <td style="color:var(--pearl-muted)"><?= (int)$f['id'] ?></td>
          <td>
            <?php if(!empty($f['imagen'])): ?>
              <img src="<?= media_url($f['imagen']) ?>" style="width:40px;height:40px;object-fit:cover;border-radius:6px;border:1px solid var(--border)" alt="">
            <?php else: ?>
              <div style="width:40px;height:40px;background:var(--card-bg);border-radius:6px;border:1px solid var(--border);display:flex;align-items:center;justify-content:center">
                <i class="fa fa-image" style="color:var(--pearl-muted);font-size:.7rem"></i>
              </div>
            <?php endif; ?>
          </td>
          <td><?= e($f['titulo'] ?? '-') ?></td>
          <td style="color:var(--teal)"><?= e($f['autor'] ?? '-') ?></td>
          <td>
            <?php if(!empty($f['aprobado'])): ?>
              <span style="background:rgba(52,211,153,.15);color:#34d399;padding:2px 8px;border-radius:20px;font-size:.75rem">Aprobado</span>
            <?php else: ?>
              <span style="background:rgba(251,191,36,.15);color:#fbbf24;padding:2px 8px;border-radius:20px;font-size:.75rem">Pendiente</span>
            <?php endif; ?>
          </td>
          <td style="color:var(--pearl-muted)"><?= format_date($f['creado_en'] ?? '') ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <p style="color:var(--pearl-muted);font-size:.78rem;margin-top:.5rem">Total: <?= count($fanarts) ?> fanart(s)</p>
  <?php endif; ?>
</div>
