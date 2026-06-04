<?php $pageTitle = 'Obras Pendientes'; ?>
<div class="d-flex align-items-center justify-content-between mb-4">
  <h2 class="fs-5 font-cinzel mb-0" style="color:var(--gold-light)">✦ Obras Pendientes</h2>
  <span class="badge-gold"><?= count($pendientes ?? []) ?> pendientes</span>
</div>

<?php if(!empty($pendientes)): ?>
<div class="row g-3">
  <?php foreach($pendientes as $o): ?>
  <div class="col-md-6 col-xl-4">
    <div class="obra-validation-card">
      <?php if(!empty($o['imagen_principal'])): ?>
        <img src="<?= media_url('Originales/imagen/Obras_digitales/'.$o['imagen_principal']) ?>"
             style="width:100%;height:180px;object-fit:cover"
             alt="<?= e($o['titulo']) ?>"
             onerror="this.src='<?= url('resources/img/placeholder.png') ?>'">
      <?php else: ?>
        <div style="height:180px;display:flex;align-items:center;justify-content:center;font-size:3rem;background:var(--purple-soft)">🎨</div>
      <?php endif; ?>
      <div class="p-3">
        <div style="font-weight:600;color:var(--pearl);margin-bottom:.3rem"><?= e($o['titulo']) ?></div>
        <div style="font-size:.78rem;color:var(--pearl-muted);margin-bottom:.5rem">
          <i class="fa fa-user me-1" style="color:var(--purple-mid)"></i><?= e($o['artista_nombre'] ?? '') ?>
          <span class="ms-2"><i class="fa fa-clock me-1" style="color:var(--gold)"></i><?= format_date($o['creado_en']) ?></span>
        </div>
        <?php if(!empty($o['descripcion'])): ?>
          <p style="font-size:.8rem;color:var(--pearl-muted);margin-bottom:.75rem;line-height:1.5">
            <?= e(truncate($o['descripcion'],100)) ?>
          </p>
        <?php endif; ?>

        <!-- Acciones validación -->
        <form method="POST" action="<?= url('curador/validar') ?>">
          <?= csrf_field() ?>
          <input type="hidden" name="obra_id" value="<?= $o['id'] ?>">
          <div class="mb-2">
            <textarea name="nota" class="form-control" rows="2"
                      placeholder="Nota para el artista (opcional)"
                      style="font-size:.8rem;resize:none"></textarea>
          </div>
          <div class="validation-actions">
            <button type="submit" name="estado" value="aprobada" class="btn-approve flex-grow-1">
              <i class="fa fa-circle-check me-1"></i>Aprobar
            </button>
            <button type="submit" name="estado" value="rechazada" class="btn-reject flex-grow-1">
              <i class="fa fa-circle-xmark me-1"></i>Rechazar
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <?php endforeach; ?>
</div>
<?php else: ?>
<div class="text-center py-5" style="color:var(--pearl-muted)">
  <i class="fa fa-circle-check fa-4x mb-3" style="color:rgba(74,222,128,.3);display:block"></i>
  <h5 class="font-cinzel">¡Todo al día!</h5>
  <p style="font-size:.85rem">No hay obras pendientes de validación</p>
</div>
<?php endif; ?>
