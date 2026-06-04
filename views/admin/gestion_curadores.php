<?php $pageTitle = 'Gestión de Curadores'; ?>
<div class="admin-page-header mb-4">
  <h4 class="font-cinzel mb-0" style="color:var(--gold-light)">
    <i class="fa fa-masks-theater me-2"></i>Gestión de Curadores
  </h4>
</div>

<div class="card-magic p-4">
  <?php if(empty($curadores)): ?>
    <p style="color:var(--pearl-muted);font-size:.9rem"><i class="fa fa-circle-info me-2" style="color:var(--teal)"></i>No hay curadores registrados aún.</p>
  <?php else: ?>
  <div style="overflow-x:auto">
    <table class="table table-dark table-hover" style="font-size:.85rem;color:var(--pearl)">
      <thead>
        <tr style="border-bottom:1px solid var(--border);color:var(--gold-light)">
          <th>#</th>
          <th>Nombre</th>
          <th>Email</th>
          <th>Especialidad</th>
          <th>Estado</th>
        </tr>
      </thead>

      <tbody>
        <?php foreach($curadores as $c): ?>
        <tr style="border-bottom:1px solid var(--border)">
          <td style="color:var(--pearl-muted)"><?= (int)$c['id'] ?></td>
          <td>
            <div style="display:flex;align-items:center;gap:.5rem">
              <img src="<?= avatar($c['avatar'] ?? '') ?>" style="width:30px;height:30px;border-radius:50%;border:1px solid var(--border-gold)" alt="">
              <?= e($c['nombre'] ?? '-') ?>
            </div>
          </td>
          <td style="color:var(--pearl-muted)"><?= e($c['email'] ?? '-') ?></td>
          <td style="color:var(--teal)"><?= e($c['especialidad'] ?? '-') ?></td>
          <td>
            <?php if(isset($c['activo']) && $c['activo']): ?>
              <span style="background:rgba(52,211,153,.15);color:#34d399;padding:2px 8px;border-radius:20px;font-size:.75rem">Activo</span>
            <?php else: ?>
              <span style="background:rgba(248,113,113,.15);color:#f87171;padding:2px 8px;border-radius:20px;font-size:.75rem">Inactivo</span>
            <?php endif; ?>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <p style="color:var(--pearl-muted);font-size:.78rem;margin-top:.5rem">Total: <?= count($curadores) ?> curador(es)</p>
  <?php endif; ?>
</div>
