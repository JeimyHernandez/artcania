<?php $pageTitle = 'Dashboard'; ?>

<div style="
background:lime;
color:black;
padding:10px;
margin-bottom:20px;
">
DASHBOARD FUNCIONANDO
</div>

<!-- Tarjetas de stats -->
<div class="row g-3 mb-4">
  <?php
  $cards = [
    ['label'=>'Usuarios',    'value'=>$stats['usuarios'],           'icon'=>'fa-users',      'color'=>'var(--gold-light)'],
    ['label'=>'Obras',       'value'=>$stats['obras'],              'icon'=>'fa-image',      'color'=>'var(--teal)'],
    ['label'=>'Artistas',    'value'=>$stats['artistas'],           'icon'=>'fa-palette',    'color'=>'#a78bfa'],
    ['label'=>'Subastas',    'value'=>$stats['subastas'],           'icon'=>'fa-gavel',      'color'=>'#f59e0b'],
    ['label'=>'Comentarios', 'value'=>$stats['comentarios'],        'icon'=>'fa-comments',   'color'=>'#34d399'],
    ['label'=>'Ventas $',    'value'=>'$'.number_format($stats['ventas'],2), 'icon'=>'fa-dollar-sign','color'=>'#f87171'],
  ];
  foreach($cards as $c): ?>
  <div class="col-6 col-md-4 col-lg-2">
    <div class="card-magic p-3 text-center h-100">
      <i class="fa <?= $c['icon'] ?> fa-2x mb-2" style="color:<?= $c['color'] ?>"></i>
      <div style="font-size:1.4rem;font-weight:700;color:var(--pearl)"><?= $c['value'] ?></div>
      <div style="font-size:.75rem;color:var(--pearl-muted)"><?= $c['label'] ?></div>
    </div>
  </div>
  <?php endforeach; ?>
</div>

<!-- Accesos rápidos -->
<div class="card-magic p-4 mb-4">
  <h6 class="font-cinzel mb-3" style="color:var(--gold-light)">Accesos rápidos</h6>
  <div class="d-flex flex-wrap gap-2">
    <a href="<?= url('admin/usuarios') ?>"        class="btn btn-sm btn-outline-magic"><i class="fa fa-users me-1"></i>Usuarios</a>
    <a href="<?= url('admin/gestion-obras') ?>"   class="btn btn-sm btn-outline-magic"><i class="fa fa-image me-1"></i>Obras</a>
    <a href="<?= url('admin/gestion-artistas') ?>" class="btn btn-sm btn-outline-magic"><i class="fa fa-palette me-1"></i>Artistas</a>
    <a href="<?= url('admin/gestion-subastas') ?>" class="btn btn-sm btn-outline-magic"><i class="fa fa-gavel me-1"></i>Subastas</a>
    <a href="<?= url('admin/gestion-roles') ?>"   class="btn btn-sm btn-outline-magic"><i class="fa fa-shield me-1"></i>Roles</a>
    <a href="<?= url('admin/bitacora') ?>"         class="btn btn-sm btn-outline-magic"><i class="fa fa-scroll me-1"></i>Bitácora</a>
    <a href="<?= url('admin/respaldos') ?>"        class="btn btn-sm btn-outline-magic"><i class="fa fa-database me-1"></i>Respaldos</a>
    <a href="<?= url('admin/configuracion') ?>"    class="btn btn-sm btn-outline-magic"><i class="fa fa-gear me-1"></i>Config</a>
  </div>
</div>

<div class="row g-3">
  <!-- Obras pendientes -->
  <div class="col-md-4">
    <div class="card-magic p-3 h-100">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="font-cinzel mb-0" style="color:var(--gold-light)">Obras pendientes</h6>
        <a href="<?= url('admin/gestion-obras') ?>" class="btn btn-sm btn-outline-magic" style="font-size:.72rem">Ver todas</a>
      </div>
      <?php if(empty($obrasPendientes)): ?>
        <p style="color:var(--pearl-muted);font-size:.85rem">Sin obras pendientes ✓</p>
      <?php else: foreach($obrasPendientes as $o): ?>
        <div class="mb-2 pb-2" style="border-bottom:1px solid var(--border)">
          <div style="font-size:.85rem;color:var(--pearl)"><?= e($o['titulo']) ?></div>
          <div style="font-size:.75rem;color:var(--pearl-muted)"><?= e($o['artista']) ?> · <?= format_date($o['creado_en']) ?></div>
        </div>
      <?php endforeach; endif; ?>
    </div>
  </div>

  <!-- Últimos usuarios -->
  <div class="col-md-4">
    <div class="card-magic p-3 h-100">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="font-cinzel mb-0" style="color:var(--gold-light)">Últimos registros</h6>
        <a href="<?= url('admin/usuarios') ?>" class="btn btn-sm btn-outline-magic" style="font-size:.72rem">Ver todos</a>
      </div>
      <?php if(empty($ultimosUsuarios)): ?>
        <p style="color:var(--pearl-muted);font-size:.85rem">Sin registros.</p>
      <?php else: foreach($ultimosUsuarios as $u): ?>
        <div class="mb-2 pb-2" style="border-bottom:1px solid var(--border)">
          <div style="font-size:.85rem;color:var(--pearl)"><?= e($u['nombre']) ?></div>
          <div style="font-size:.75rem;color:var(--pearl-muted)">
            <span style="color:var(--teal)"><?= e($u['rol']) ?></span>
            · <?= format_date($u['creado_en']) ?>
          </div>
        </div>
      <?php endforeach; endif; ?>
    </div>
  </div>

  <!-- Últimos logs -->
  <div class="col-md-4">
    <div class="card-magic p-3 h-100">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="font-cinzel mb-0" style="color:var(--gold-light)">Actividad reciente</h6>
        <a href="<?= url('admin/bitacora') ?>" class="btn btn-sm btn-outline-magic" style="font-size:.72rem">Ver todo</a>
      </div>
      <?php if(empty($ultimosLogs)): ?>
        <p style="color:var(--pearl-muted);font-size:.85rem">Sin registros.</p>
      <?php else: foreach($ultimosLogs as $l): ?>
        <div class="mb-2 pb-2" style="border-bottom:1px solid var(--border)">
          <div style="font-size:.75rem">
            <span class="badge-magic" style="font-size:.65rem"><?= e($l['accion']) ?></span>
            <span style="color:var(--pearl-muted)"> <?= e($l['nombre'] ?? 'Sistema') ?></span>
          </div>
          <div style="font-size:.7rem;color:var(--pearl-muted)"><?= format_date($l['creado_en']) ?></div>
        </div>
      <?php endforeach; endif; ?>
    </div>
  </div>
</div>
