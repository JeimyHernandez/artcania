<?php $pageTitle = 'Mis Fanarts'; ?>
<div class="d-flex align-items-center justify-content-between mb-4">
  <h2 class="fs-5 font-cinzel mb-0" style="color:var(--gold-light)">✦ Mis Fanarts</h2>
</div>

<?php if(!empty($fanarts)): ?>
<div class="row g-3">
  <?php foreach($fanarts as $f): ?>
  <div class="col-sm-6 col-md-4 col-xl-3">
    <div class="card-magic overflow-hidden">
      <?php if(!empty($f['imagen'])): ?>
        <img src="<?= media_url('Originales/imagen/FanArts/'.$f['imagen']) ?>"
             style="width:100%;height:160px;object-fit:cover"
             alt="<?= e($f['titulo']) ?>"
             onerror="this.src='<?= url('resources/img/placeholder.png') ?>'">
      <?php else: ?>
        <div style="height:160px;display:flex;align-items:center;justify-content:center;font-size:2.5rem;background:var(--purple-soft)">🎨</div>
      <?php endif; ?>
      <div class="p-3">
        <div style="font-size:.88rem;font-weight:600;color:var(--pearl);margin-bottom:4px">
          <?= e(truncate($f['titulo'], 36)) ?>
        </div>
        <div style="font-size:.75rem;color:var(--pearl-muted);margin-bottom:6px">
          Por: <span style="color:var(--teal)"><?= e($f['creador']) ?></span>
          <?php if(!empty($f['obra_titulo'])): ?>
            · Sobre: <em><?= e(truncate($f['obra_titulo'], 24)) ?></em>
          <?php endif; ?>
        </div>
        <div class="d-flex align-items-center justify-content-between">
          <span style="font-size:.7rem;background:<?= $f['aprobado'] ? 'rgba(52,211,153,.15)' : 'rgba(251,191,36,.15)' ?>;color:<?= $f['aprobado'] ? '#34d399' : '#fbbf24' ?>;padding:2px 8px;border-radius:20px">
            <?= $f['aprobado'] ? 'Aprobado' : 'Pendiente' ?>
          </span>
          <span style="font-size:.72rem;color:var(--pearl-muted)"><?= format_date($f['creado_en']) ?></span>
        </div>
      </div>
    </div>
  </div>
  <?php endforeach; ?>
</div>
<?php else: ?>
<div class="card-magic p-5 text-center" style="color:var(--pearl-muted)">
  <i class="fa fa-star fa-3x mb-3" style="opacity:.2;display:block"></i>
  <h5 class="font-cinzel">Sin fanarts aún</h5>
  <p style="font-size:.85rem">Los fans aún no han creado fanarts de tus obras.</p>
</div>
<?php endif; ?>
