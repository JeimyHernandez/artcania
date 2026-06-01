<?php $pageTitle = 'Dashboard Artista'; ?>
<!-- Encabezado principal del panel del artista -->
<h2 class="fw-bold mb-4">
  <i class="fa fa-palette me-2"></i>Mi Studio
</h2>
<!-- Tarjetas de estadísticas rápidas -->
<div class="row g-4 mb-4">
  <!-- Total de obras registradas -->
  <div class="col-6 col-md-3">
    <div class="card bg-primary text-white shadow">
      <div class="card-body text-center">
        <h3 class="fw-bold"><?= count($obras) ?></h3>
        <small>Mis Obras</small>
      </div>
    </div>
  </div>

  <!-- Total de obras aprobadas -->
  <div class="col-6 col-md-3">
    <div class="card bg-success text-white shadow">
      <div class="card-body text-center">
        <h3 class="fw-bold">
          <?= count(array_filter($obras, fn($o) => $o['estado'] === 'aprobada')) ?>
        </h3>
        <small>Aprobadas</small>
      </div>
    </div>
  </div>

  <!-- Total de obras pendientes -->
  <div class="col-6 col-md-3">
    <div class="card bg-warning text-white shadow">
      <div class="card-body text-center">
        <h3 class="fw-bold">
          <?= count(array_filter($obras, fn($o) => $o['estado'] === 'pendiente')) ?>
        </h3>
        <small>Pendientes</small>
      </div>
    </div>
  </div>

  <!-- Total acumulado de visualizaciones -->
  <div class="col-6 col-md-3">
    <div class="card bg-info text-white shadow">
      <div class="card-body text-center">
        <h3 class="fw-bold">
          <?= array_sum(array_column($obras, 'visualizaciones')) ?>
        </h3>
        <small>Visualizaciones</small>
      </div>
    </div>
  </div>
</div>
<div class="row g-4">

  <!-- Tabla de últimas obras publicadas -->
  <div class="col-md-8">
    <div class="card shadow">
  <div class="card-header d-flex justify-content-between fw-bold">
    <span>
      <i class="fa fa-images me-2"></i>Últimas Obras
    </span>
    <a href="<?= url('artista/obras') ?>"
       class="btn btn-sm btn-outline-primary">
      Ver todas
    </a>
  </div>
  <div class="table-responsive">
    <table class="table table-hover mb-0 tabla-dt">
      <thead class="table-light">
        <tr>
          <th>Título</th>
          <th>Estado</th>
          <th>Vistas</th>
          <th>Fecha</th>
        </tr>
      </thead>
      <tbody>

        <!-- Mostrar únicamente las 5 obras más recientes -->
        <?php foreach(array_slice($obras, 0, 5) as $o): ?>
        <tr>
          <td>
            <?= e(truncate($o['titulo'], 35)) ?>
          </td>
          <td>
            <span class="badge bg-<?= $o['estado'] === 'aprobada'
              ? 'success'
              : ($o['estado'] === 'pendiente'
                  ? 'warning'
                  : 'danger') ?>">
              <?= e($o['estado']) ?>
            </span>
          </td>
          <td>
            <?= number_format($o['visualizaciones'] ?? 0) ?>
          </td>
          <td class="small">
            <?= format_date($o['creado_en'], 'd/m/Y') ?>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
  </div>

  <!-- Panel de accesos rápidos -->
  <div class="col-md-4">
    <div class="card shadow">
  <div class="card-header fw-bold">
    <i class="fa fa-upload me-2"></i>Subir Nueva Obra
  </div>
  <div class="card-body">

    <!-- Acceso al formulario de publicación -->
    <a href="<?= url('artista/obras') ?>"
       class="btn btn-primary w-100 mb-2">
      <i class="fa fa-plus me-2"></i>Subir Obra
    </a>
    
    <!-- Acceso al sistema de mensajería -->
    <a href="<?= url('chat') ?>"
       class="btn btn-outline-secondary w-100">
      <i class="fa fa-comments me-2"></i>Mis Mensajes
    </a>
  </div>
</div>
  </div>
</div>
