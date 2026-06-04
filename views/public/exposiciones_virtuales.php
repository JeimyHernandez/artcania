<?php $pageTitle = 'Exposiciones Virtuales'; ?>
<div class="container-xl py-4">

  <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
    <h1 class="fs-5 font-cinzel mb-0">✦ Exposiciones Virtuales</h1>
    <span class="badge-magic"><?= count($exposiciones ?? []) ?> disponibles</span>
  </div>

  <?php if(!empty($exposiciones)): ?>
  <div class="row g-4">
    <?php foreach($exposiciones as $ex): ?>
    <div class="col-md-6 col-xl-4">
      <div class="card-magic p-4 h-100 d-flex flex-column"
           style="border:1px solid rgba(124,58,237,.2);transition:transform .25s,box-shadow .25s"
           onmouseover="this.style.transform='translateY(-4px)';this.style.boxShadow='0 12px 30px rgba(124,58,237,.25)'"
           onmouseout="this.style.transform='';this.style.boxShadow=''">

        <!-- Icono tipo -->
        <div style="display:flex;align-items:center;gap:.75rem;margin-bottom:1rem">
          <div style="width:48px;height:48px;border-radius:12px;background:rgba(124,58,237,.15);border:1px solid rgba(124,58,237,.3);display:flex;align-items:center;justify-content:center;font-size:1.4rem;flex-shrink:0">
            <?php
            $icons = ['virtual'=>'🖥️','presencial'=>'🏛️','hibrida'=>'🌐'];
            echo $icons[$ex['tipo'] ?? 'virtual'] ?? '🎨';
            ?>
          </div>
          <div>
            <span style="font-size:.7rem;color:var(--pearl-muted);text-transform:uppercase;letter-spacing:.06em">
              <?= ucfirst($ex['tipo'] ?? 'virtual') ?>
            </span>
            <div style="font-size:.75rem;color:var(--teal)">
              <?= e($ex['curador_nombre'] ?? 'Artcania') ?>
            </div>
          </div>
        </div>

        <!-- Título -->
        <h5 class="font-cinzel mb-2" style="font-size:1rem;color:var(--pearl)">
          <?= e($ex['titulo'] ?? '') ?>
        </h5>

        <!-- Descripción -->
        <?php if(!empty($ex['descripcion'])): ?>
          <p style="font-size:.83rem;color:var(--pearl-muted);line-height:1.6;flex-grow:1;margin-bottom:.75rem">
            <?= nl2br(e(truncate($ex['descripcion'], 120))) ?>
          </p>
        <?php else: ?>
          <div style="flex-grow:1"></div>
        <?php endif; ?>

        <!-- Fechas -->
        <div style="font-size:.75rem;color:var(--pearl-muted);margin-bottom:.75rem;display:flex;gap:1rem">
          <?php if(!empty($ex['fecha_inicio'])): ?>
            <span><i class="fa fa-calendar-day me-1" style="color:var(--purple-mid)"></i>
              Inicio: <?= date('d/m/Y', strtotime($ex['fecha_inicio'])) ?>
            </span>
          <?php endif; ?>
          <?php if(!empty($ex['fecha_fin'])): ?>
            <span><i class="fa fa-calendar-xmark me-1" style="color:var(--gold)"></i>
              Fin: <?= date('d/m/Y', strtotime($ex['fecha_fin'])) ?>
            </span>
          <?php endif; ?>
        </div>

        <!-- Badge estado -->
        <div>
          <span style="background:rgba(52,211,153,.15);color:#34d399;padding:3px 12px;border-radius:20px;font-size:.72rem;font-weight:600">
            <i class="fa fa-circle-check me-1"></i>Activa
          </span>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>

  <?php else: ?>
  <div class="text-center py-5" style="color:var(--pearl-muted)">
    <i class="fa fa-landmark fa-4x mb-3" style="opacity:.2;display:block"></i>
    <h5 class="font-cinzel">No hay exposiciones disponibles</h5>
    <p style="font-size:.85rem">Vuelve pronto, se vienen nuevas exposiciones.</p>
    <a href="<?= url('galeria') ?>" class="btn btn-outline-magic btn-sm mt-2">Explorar galería</a>
  </div>
  <?php endif; ?>

</div>
