<?php $pageTitle = 'Moderar Comentarios'; ?>
<h2 class="fw-bold mb-4"><i class="fa fa-comments me-2"></i>Moderación de Comentarios</h2>
<?php if(!isset($comentarios)) $comentarios = []; ?>
<div class="card shadow">
  <div class="card-body p-0">
    <?php foreach($comentarios as $c): ?>
    <div class="border-bottom p-4">
      <div class="d-flex justify-content-between align-items-start">
        <div class="flex-grow-1">
          <strong><?= e($c['nombre']) ?></strong> en "<em><?= e($c['titulo']) ?></em>"
          <p class="mt-1 mb-2"><?= nl2br(e($c['contenido'])) ?></p>
          <small class="text-muted"><?= format_date($c['creado_en']) ?></small>
        </div>
        <div class="ms-3 d-flex flex-column gap-1">
          <form method="POST" action="<?= url('curador/comentario/aprobar') ?>" class="d-inline">
            <?= csrf_field() ?><input type="hidden" name="id" value="<?= $c['id'] ?>">
            <button type="submit" class="btn btn-success btn-sm"><i class="fa fa-check"></i> Aprobar</button>
          </form>
          <form method="POST" action="<?= url('curador/comentario/rechazar') ?>" class="d-inline">
            <?= csrf_field() ?><input type="hidden" name="id" value="<?= $c['id'] ?>">
            <button type="submit" class="btn btn-danger btn-sm"><i class="fa fa-times"></i> Rechazar</button>
          </form>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
    <?php if(empty($comentarios)): ?><div class="p-5 text-center text-muted"><i class="fa fa-check-circle fa-3x text-success mb-3 d-block"></i>No hay comentarios pendientes.</div><?php endif; ?>
  </div>
</div>
