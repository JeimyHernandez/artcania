<?php $pageTitle = 'Mis Obras'; ?>
<div class="d-flex align-items-center justify-content-between mb-4">
  <h2 class="fs-5 font-cinzel mb-0" style="color:var(--gold-light)">✦ Mis Obras</h2>
  <a href="<?= url('artista/subir') ?>" class="btn btn-magic btn-sm">
    <i class="fa fa-cloud-arrow-up me-1"></i>Nueva obra
  </a>
</div>

<?php if(!empty($obras)): ?>
<div class="row g-3">
  <?php foreach($obras as $o):
    $estadoMap = [
      'aprobada'  => ['class'=>'badge-teal',   'label'=>'✓ Aprobada'],
      'pendiente' => ['class'=>'badge-warning', 'label'=>'⏳ Pendiente'],
      'rechazada' => ['class'=>'badge-danger',  'label'=>'✗ Rechazada'],
    ];
    $es = $estadoMap[$o['estado']] ?? ['class'=>'badge-magic','label'=>$o['estado']];
  ?>
  <div class="col-sm-6 col-md-4 col-xl-3">
    <div class="obra-card-mini">
      <?php if(!empty($o['imagen_principal'])): ?>
        <img src="<?= media_url('Originales/imagen/Obras_digitales/'.$o['imagen_principal']) ?>"
             alt="<?= e($o['titulo']) ?>"
             onerror="this.src='<?= url('resources/img/placeholder.png') ?>'">
      <?php else: ?>
        <div style="height:140px;display:flex;align-items:center;justify-content:center;font-size:2.5rem;background:var(--purple-soft)">🎨</div>
      <?php endif; ?>
      <div class="card-body">
        <div class="d-flex align-items-start justify-content-between gap-1 mb-2">
          <span style="font-size:.85rem;font-weight:600;color:var(--pearl)">
            <?= e(truncate($o['titulo'],28)) ?>
          </span>
          <span class="<?= $es['class'] ?>" style="font-size:.65rem;white-space:nowrap"><?= $es['label'] ?></span>
        </div>
        <div class="d-flex align-items-center gap-3 mb-2" style="font-size:.75rem;color:var(--pearl-muted)">
          <span><i class="fa fa-eye me-1" style="color:var(--teal)"></i><?= number_format($o['visualizaciones'] ?? 0) ?></span>
          <?php if(!empty($o['precio'])): ?>
            <span><i class="fa fa-tag me-1" style="color:var(--gold)"></i>$<?= number_format($o['precio'],0) ?></span>
          <?php endif; ?>
        </div>
        <?php if(!empty($o['nota_curador'])): ?>
          <div class="mb-2 p-2" style="background:rgba(248,113,113,.08);border:1px solid rgba(248,113,113,.2);border-radius:8px;font-size:.75rem;color:#fca5a5">
            <i class="fa fa-comment me-1"></i><?= e(truncate($o['nota_curador'],50)) ?>
          </div>
        <?php endif; ?>
        <a href="<?= url('artista/obras/'.$o['id'].'/editar') ?>"
           class="btn btn-sm btn-outline-magic w-100" style="font-size:.75rem">
          <i class="fa fa-pen-to-square me-1"></i>Editar
        </a>
      </div>
    </div>
  </div>
  <?php endforeach; ?>
</div>
<?php else: ?>
<div class="text-center py-5" style="color:var(--pearl-muted)">
  <i class="fa fa-images fa-4x mb-3" style="opacity:.2;display:block"></i>
  <h5 class="font-cinzel">Aún no has subido obras</h5>
  <p style="font-size:.85rem">Comparte tu arte con el mundo mágico de Artcania</p>
  <a href="<?= url('artista/subir') ?>" class="btn btn-magic px-4">
    <i class="fa fa-cloud-arrow-up me-2"></i>Subir primera obra
  </a>
</div>
<?php endif; ?>
