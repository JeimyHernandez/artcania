<?php $pageTitle = 'Mis Favoritos'; ?>
<div class="d-flex align-items-center justify-content-between mb-4">
  <h2 class="fs-5 font-cinzel mb-0">✦ Mis Favoritos</h2>
  <span class="badge-magic"><?= count($favs ?? []) ?></span>
</div>

<?php if(!empty($favs)): ?>
<div class="gallery-grid">
  <?php foreach($favs as $f): ?>
  <div class="gallery-item" onclick="location.href='<?= url('galeria/'.$f['obra_id']) ?>'">
    <?php if(!empty($f['imagen_principal'])): ?>
      <img src="<?= media_url('Originales/imagen/Obras_digitales/'.$f['imagen_principal']) ?>"
           alt="<?= e($f['titulo'] ?? '') ?>" loading="lazy"
           onerror="this.src='<?= url('resources/img/placeholder.png') ?>'">
    <?php else: ?>
      <div style="height:180px;background:var(--purple-soft);display:flex;align-items:center;justify-content:center;font-size:2rem">🎨</div>
    <?php endif; ?>
    <button class="gallery-fav-btn fav-btn active" data-id="<?= $f['obra_id'] ?>"
            onclick="event.stopPropagation()" title="Quitar favorito">
      <i class="fa-solid fa-heart" style="color:#f472b6"></i>
    </button>
    <div class="gallery-item-overlay">
      <div class="obra-title"><?= e(truncate($f['titulo'] ?? '',35)) ?></div>
      <div class="obra-artist">✦ <?= e($f['artista_nombre'] ?? '') ?></div>
    </div>
  </div>
  <?php endforeach; ?>
</div>
<?php else: ?>
<div class="text-center py-5" style="color:var(--pearl-muted)">
  <i class="fa fa-heart fa-4x mb-3" style="opacity:.2;display:block"></i>
  <h5 class="font-cinzel">Aún no tienes favoritos</h5>
  <p style="font-size:.85rem">Explora la galería y guarda las obras que más te gusten</p>
  <a href="<?= url('galeria') ?>" class="btn btn-magic px-4">
    <i class="fa fa-compass me-2"></i>Explorar galería
  </a>
</div>
<?php endif; ?>
