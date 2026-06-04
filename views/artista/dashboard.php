<?php $pageTitle = 'Dashboard Artista'; ?>
<div class="mb-4">
  <h1 class="fs-4 mb-1">
    <i class="fa fa-sparkles me-2" style="color:var(--gold-light)"></i>
    ¡Bienvenida de nuevo, <?= e(explode(' ', Auth::user()['nombre'])[0]) ?>! ✦
  </h1>
  <p style="color:var(--pearl-muted);font-size:.85rem">Sigue creando magia. Tu arte inspira universos.</p>
</div>

<!-- Stats -->
<?php
$totalObras    = count($obras ?? []);
$aprobadas     = count(array_filter($obras ?? [], fn($o) => $o['estado']==='aprobada'));
$pendientes    = count(array_filter($obras ?? [], fn($o) => $o['estado']==='pendiente'));
$totalVistas   = array_sum(array_column($obras ?? [], 'visualizaciones'));
?>
<div class="row g-3 mb-4">
  <div class="col-6 col-xl-3">
    <div class="stat-card">
      <div class="stat-icon purple"><i class="fa fa-eye"></i></div>
      <div class="stat-value"><?= number_format($totalVistas) ?></div>
      <div class="stat-label">Visitas Totales</div>
    </div>
  </div>
  <div class="col-6 col-xl-3">
    <div class="stat-card">
      <div class="stat-icon gold"><i class="fa fa-images"></i></div>
      <div class="stat-value"><?= $totalObras ?></div>
      <div class="stat-label">Obras</div>
    </div>
  </div>
  <div class="col-6 col-xl-3">
    <div class="stat-card">
      <div class="stat-icon teal"><i class="fa fa-circle-check"></i></div>
      <div class="stat-value"><?= $aprobadas ?></div>
      <div class="stat-label">Aprobadas</div>
    </div>
  </div>
  <div class="col-6 col-xl-3">
    <div class="stat-card">
      <div class="stat-icon pink"><i class="fa fa-hourglass-half"></i></div>
      <div class="stat-value"><?= $pendientes ?></div>
      <div class="stat-label">Pendientes</div>
    </div>
  </div>
</div>

<!-- Acciones + obras recientes -->
<div class="row g-3">
  <div class="col-lg-4">
    <div class="card-magic p-3 mb-3">
      <h6 class="font-cinzel mb-3" style="color:var(--gold-light)">✦ Acciones Rápidas</h6>
      <a href="<?= url('artista/subir') ?>" class="btn btn-magic w-100 mb-2">
        <i class="fa fa-cloud-arrow-up me-2"></i>Subir nueva obra
      </a>
      <a href="<?= url('artista/editar-perfil') ?>" class="btn btn-outline-magic w-100 mb-2">
        <i class="fa fa-user-pen me-2"></i>Editar mi perfil
      </a>
      <a href="<?= url('artista/metricas') ?>" class="btn btn-outline-magic w-100">
        <i class="fa fa-chart-line me-2"></i>Ver métricas
      </a>
    </div>
  </div>

  <div class="col-lg-8">
    <div class="card-magic p-3">
      <div class="d-flex align-items-center justify-content-between mb-3">
        <h6 class="font-cinzel mb-0" style="color:var(--gold-light)">✦ Obras Recientes</h6>
        <a href="<?= url('artista/obras') ?>" class="btn btn-sm btn-outline-magic">Ver todas</a>
      </div>
      <?php if(!empty($obras)): ?>
      <div class="row g-2">
        <?php foreach(array_slice($obras, 0, 5) as $o): ?>
        <div class="col-6 col-md-4">
          <div class="card-magic" style="border-radius:12px">
            <?php if(!empty($o['imagen_principal'])): ?>
              <img src="<?= media_url('Originales/imagen/Obras_digitales/'.$o['imagen_principal']) ?>"
                   style="width:100%;height:110px;object-fit:cover;border-radius:11px 11px 0 0"
                   alt="<?= e($o['titulo']) ?>"
                   onerror="this.src='<?= url('resources/img/placeholder.png') ?>'">
            <?php else: ?>
              <div style="height:110px;display:flex;align-items:center;justify-content:center;font-size:2rem;background:var(--purple-soft);border-radius:11px 11px 0 0">🎨</div>
            <?php endif; ?>
            <div class="p-2">
              <div style="font-size:.78rem;font-weight:600;color:var(--pearl);white-space:nowrap;overflow:hidden;text-overflow:ellipsis">
                <?= e($o['titulo']) ?>
              </div>
              <div class="mt-1">
                <?php
                $estadoMap = [
                  'aprobada'  => ['class'=>'badge-teal',    'label'=>'✓ Aprobada'],
                  'pendiente' => ['class'=>'badge-warning',  'label'=>'⏳ Pendiente'],
                  'rechazada' => ['class'=>'badge-danger',   'label'=>'✗ Rechazada'],
                ];
                $es = $estadoMap[$o['estado'] ?? ''] ?? ['class'=>'badge-magic','label'=>$o['estado'] ?? ''];
                ?>
                <span class="<?= $es['class'] ?>" style="font-size:.65rem"><?= $es['label'] ?></span>
              </div>
            </div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
      <?php else: ?>
      <div class="text-center py-4" style="color:var(--pearl-muted)">
        <i class="fa fa-image fa-2x mb-2" style="opacity:.3"></i>
        <p class="mb-2">Aún no tienes obras</p>
        <a href="<?= url('artista/subir') ?>" class="btn btn-magic btn-sm">Subir primera obra</a>
      </div>
      <?php endif; ?>
    </div>
  </div>
</div>
