<?php $pageTitle = 'Mi Perfil'; ?>
<div class="row g-4">
  <!-- Perfil card -->
  <div class="col-md-4 col-lg-3">
    <div class="card-magic p-4 text-center">
      <div class="position-relative d-inline-block mb-3">
        <img src="<?= avatar($user['avatar'] ?? '') ?>"
             class="rounded-circle"
             style="width:100px;height:100px;object-fit:cover;border:3px solid var(--gold);box-shadow:0 0 25px var(--gold-glow)"
             alt="<?= e($user['nombre']) ?>">
      </div>
      <h5 class="font-cinzel mb-1"><?= e($user['nombre']) ?></h5>
      <div class="badge-magic mb-2"><?= ucfirst($user['rol'] ?? 'usuario') ?></div>
      <?php if(!empty($user['bio'])): ?>
        <p style="font-size:.83rem;color:var(--pearl-dim);line-height:1.6"><?= nl2br(e($user['bio'])) ?></p>
      <?php endif; ?>
      <div class="divider-magic my-3">✦</div>
      <div class="d-flex justify-content-around">
        <div class="text-center">
          <div style="font-family:'Cinzel',serif;font-size:1.2rem;color:var(--pearl)"><?= count($favs ?? []) ?></div>
          <div style="font-size:.7rem;color:var(--pearl-muted);text-transform:uppercase">Favoritos</div>
        </div>
        <div class="text-center">
          <div style="font-family:'Cinzel',serif;font-size:1.2rem;color:var(--pearl)"><?= count($notif ?? []) ?></div>
          <div style="font-size:.7rem;color:var(--pearl-muted);text-transform:uppercase">Notif.</div>
        </div>
      </div>
      <a href="<?= url('editar-perfil') ?>" class="btn btn-outline-magic btn-sm w-100 mt-3">
        <i class="fa fa-pen-to-square me-1"></i>Editar perfil
      </a>
    </div>
  </div>

  <!-- Actividad reciente -->
  <div class="col-md-8 col-lg-9">
    <!-- Favoritos recientes -->
    <div class="card-magic p-3 mb-3">
      <div class="d-flex align-items-center justify-content-between mb-3">
        <h6 class="font-cinzel mb-0" style="color:var(--gold-light)">✦ Mis Favoritos</h6>
        <a href="<?= url('mis-favoritos') ?>" class="btn btn-sm btn-outline-magic">Ver todos</a>
      </div>
      <?php if(!empty($favs)): ?>
      <div class="row g-2">
        <?php foreach(array_slice($favs, 0, 6) as $f): ?>
        <div class="col-4 col-md-2">
          <a href="<?= url('galeria/'.$f['obra_id']) ?>">
            <img src="<?= media_url('Originales/imagen/Obras_digitales/'.($f['imagen_principal'] ?? '')) ?>"
                 class="w-100 rounded"
                 style="height:70px;object-fit:cover;border:1px solid var(--border);transition:border-color .2s"
                 onmouseover="this.style.borderColor='var(--purple-mid)'"
                 onmouseout="this.style.borderColor='var(--border)'"
                 alt=""
                 onerror="this.src='<?= url('resources/img/placeholder.png') ?>'">
          </a>
        </div>
        <?php endforeach; ?>
      </div>
      <?php else: ?>
        <p style="color:var(--pearl-muted);font-size:.85rem">Aún no tienes obras favoritas.</p>
      <?php endif; ?>
    </div>

    <!-- Notificaciones recientes -->
    <div class="card-magic p-3">
      <div class="d-flex align-items-center justify-content-between mb-3">
        <h6 class="font-cinzel mb-0" style="color:var(--gold-light)">✦ Notificaciones</h6>
        <a href="<?= url('notificaciones') ?>" class="btn btn-sm btn-outline-magic">Ver todas</a>
      </div>
      <?php if(!empty($notif)): ?>
        <?php foreach(array_slice($notif, 0, 5) as $n): ?>
        <div class="d-flex align-items-start gap-3 pb-2 mb-2"
             style="border-bottom:1px solid var(--border);opacity:<?= $n['leida'] ? '.6' : '1' ?>">
          <i class="fa fa-bell mt-1 flex-shrink-0" style="color:var(--<?= $n['leida'] ? 'pearl-muted' : 'gold-light' ?>)"></i>
          <div>
            <div style="font-size:.83rem;color:var(--pearl)"><?= e($n['mensaje']) ?></div>
            <div style="font-size:.72rem;color:var(--pearl-muted)"><?= format_date($n['creado_en']) ?></div>
          </div>
        </div>
        <?php endforeach; ?>
      <?php else: ?>
        <p style="color:var(--pearl-muted);font-size:.85rem">No tienes notificaciones nuevas.</p>
      <?php endif; ?>
    </div>
  </div>
</div>


