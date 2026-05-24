<?php
/** @var array $obra */
/** @var array $comentarios */

$obra = $obra ?? [];
$obra += [
  'titulo' => 'Obra no encontrada',
  'imagen_principal' => '',
  'artista_nombre' => 'Desconocido',
  'visualizaciones' => 0,
  'valoracion_promedio' => 0,
  'precio' => 0,
  'descripcion' => '',
  'tecnica' => '',
  'dimensiones' => '',
  'creado_en' => date('Y-m-d'),
  'id' => 0,
];
$pageTitle = $obra['titulo'];
?>
<div class="container py-5">
  <nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb"><li class="breadcrumb-item"><a href="<?=url('galeria')?>">Galería</a></li>
    <li class="breadcrumb-item active"><?= e(truncate($obra['titulo'],30)) ?></li></ol>
  </nav>
  <div class="row g-5">
    <div class="col-md-7">
      <?php if($obra['imagen_principal']): ?>
      <img src="<?= media_url('Originales/imagen/Obras_digitales/'.$obra['imagen_principal']) ?>" class="img-fluid rounded shadow-lg w-100" alt="<?=e($obra['titulo'])?>">
      <?php else: ?>
      <div class="bg-light rounded d-flex align-items-center justify-content-center" style="height:400px"><i class="fa fa-image fa-5x text-muted"></i></div>
      <?php endif; ?>
    </div>
    <div class="col-md-5">
      <h1 class="fw-bold mb-2"><?= e($obra['titulo']) ?></h1>
      <p class="text-muted mb-3">por <strong><?= e($obra['artista_nombre']) ?></strong></p>
      <div class="d-flex gap-3 mb-4">
        <span class="badge bg-light text-dark"><i class="fa fa-eye me-1"></i><?= number_format($obra['visualizaciones']??0) ?> vistas</span>
        <?php if($obra['valoracion_promedio']??0): ?>
        <span class="badge bg-warning text-dark"><i class="fa fa-star me-1"></i><?= number_format($obra['valoracion_promedio'],1) ?></span>
        <?php endif; ?>
      </div>
      <?php if($obra['precio']): ?>
      <div class="alert alert-success py-2"><h4 class="mb-0 text-success fw-bold"><?= money($obra['precio']) ?></h4></div>
      <?php endif; ?>
      <div class="mb-4">
        <h6 class="fw-bold">Descripción</h6>
        <p class="text-muted"><?= nl2br(e($obra['descripcion'])) ?></p>
      </div>
      <?php if($obra['tecnica']): ?><p><strong>Técnica:</strong> <?= e($obra['tecnica']) ?></p><?php endif; ?>
      <?php if($obra['dimensiones']): ?><p><strong>Dimensiones:</strong> <?= e($obra['dimensiones']) ?></p><?php endif; ?>
      <p class="text-muted small"><i class="fa fa-calendar me-1"></i>Publicada: <?= format_date($obra['creado_en'],'d/m/Y') ?></p>
      <?php if(Auth::check()): ?>
      <div class="d-flex gap-2 mt-3">
        <button class="btn btn-outline-danger btn-sm" onclick="toggleFav(<?= $obra['id'] ?>)"><i class="fa fa-heart me-1"></i>Favorito</button>
        <a href="<?= url('chat') ?>" class="btn btn-outline-primary btn-sm"><i class="fa fa-comments me-1"></i>Contactar artista</a>
      </div>
      <?php endif; ?>
    </div>
  </div>
  <!-- Comentarios -->
  <div class="mt-5">
    <h4 class="fw-bold border-bottom pb-2 mb-4">Comentarios (<?= count($comentarios) ?>)</h4>
    <?php foreach($comentarios as $c): ?>
    <div class="d-flex gap-3 mb-3">
      <img src="<?= avatar($c['avatar']??'') ?>" class="rounded-circle flex-shrink-0" width="42" height="42" style="object-fit:cover">
      <div class="bg-light rounded p-3 flex-grow-1">
        <strong class="d-block"><?= e($c['nombre']) ?></strong>
        <small class="text-muted"><?= format_date($c['creado_en']) ?></small>
        <p class="mb-0 mt-1"><?= nl2br(e($c['contenido'])) ?></p>
      </div>
    </div>
    <?php endforeach; ?>
    <?php if(Auth::check()): ?>
    <form method="POST" action="<?= url('comentario') ?>" class="mt-4">
      <?= csrf_field() ?>
      <input type="hidden" name="obra_id" value="<?= $obra['id'] ?>">
      <div class="mb-2"><textarea name="texto" rows="3" class="form-control" placeholder="Escribe un comentario..." required minlength="5"></textarea></div>
      <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-paper-plane me-1"></i>Comentar</button>
    </form>
    <?php endif; ?>
  </div>
</div>
<script>
function toggleFav(id){
  fetch('<?=url('favorito/toggle')?>', {
    method:'POST',
    headers:{'Content-Type':'application/x-www-form-urlencoded','X-CSRF-TOKEN':'<?=csrf_token()?>','X-Requested-With':'XMLHttpRequest'},
    body:'obra_id='+id
  }).then(r=>r.json()).then(d=>{console.log(d)});
}
</script>
