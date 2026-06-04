<?php $pageTitle = e($album['nombre']); ?>

<div class="d-flex justify-content-between align-items-start mb-4 flex-wrap gap-2">
  <div>
    <h2 class="fs-4 font-cinzel mb-1" style="color:var(--gold-light)">✦ <?= e($album['nombre']) ?></h2>
    <p style="color:var(--pearl-muted);max-width:640px;margin:0 0 4px">
      <?= e($album['descripcion'] ?? '') ?: '<em>Sin descripción</em>' ?>
    </p>
    <small style="color:var(--pearl-dim)">
      <?= count($album['obras']) ?> obra(s) ·
      <span style="color:<?= $album['publico'] ? '#34d399' : '#f87171' ?>">
        <?= $album['publico'] ? 'Público' : 'Privado' ?>
      </span>
    </small>
  </div>
  <a href="<?= url('mis-albumes') ?>" class="btn btn-outline-magic">
    <i class="fa fa-arrow-left me-1"></i>Volver
  </a>
</div>

<?php if(empty($album['obras'])): ?>
  <div class="card-magic p-5 text-center" style="color:var(--pearl-muted)">
    <i class="fa fa-images fa-3x mb-3" style="opacity:.3;display:block"></i>
    <p>Este álbum aún no contiene obras.</p>
    <a href="<?= url('galeria') ?>" class="btn btn-magic mt-2">
      <i class="fa fa-compass me-1"></i>Explorar galería
    </a>
  </div>
<?php else: ?>
<div class="row g-3" id="albumObras" data-album="<?= (int)$album['id'] ?>">
  <?php foreach($album['obras'] as $o): ?>
  <div class="col-md-3 col-sm-6" id="ao-<?= (int)$o['id'] ?>">
    <div class="card-magic overflow-hidden">
      <a href="<?= url('galeria/'.$o['id']) ?>" style="display:block;overflow:hidden">
        <?php if(!empty($o['imagen_principal'])): ?>
          <img src="<?= media_url('Originales/imagen/Obras_digitales/'.$o['imagen_principal']) ?>"
               style="width:100%;height:160px;object-fit:cover;transition:transform .5s"
               alt="<?= e($o['titulo']) ?>"
               onmouseover="this.style.transform='scale(1.06)'"
               onmouseout="this.style.transform=''"
               onerror="this.src='<?= url('resources/img/placeholder.png') ?>'">
        <?php else: ?>
          <div style="height:160px;display:flex;align-items:center;justify-content:center;font-size:2.5rem;background:var(--purple-soft)">🎨</div>
        <?php endif; ?>
      </a>
      <div class="p-2 d-flex align-items-start justify-content-between gap-1">
        <div style="overflow:hidden">
          <div style="font-size:.85rem;font-weight:600;color:var(--pearl);white-space:nowrap;overflow:hidden;text-overflow:ellipsis">
            <?= e(truncate($o['titulo'], 32)) ?>
          </div>
          <div style="font-size:.72rem;color:var(--pearl-muted)">
            <?= e($o['categoria'] ?? 'Sin categoría') ?> · <?= e($o['artista']) ?>
          </div>
        </div>
        <?php if($esPropietario): ?>
        <button type="button" class="js-quitar flex-shrink-0"
                data-obra="<?= (int)$o['id'] ?>"
                title="Quitar del álbum"
                style="background:rgba(248,113,113,.12);color:#f87171;border:1px solid rgba(248,113,113,.3);border-radius:6px;padding:3px 8px;font-size:.75rem;cursor:pointer">
          <i class="fa fa-xmark"></i>
        </button>
        <?php endif; ?>
      </div>
    </div>
  </div>
  <?php endforeach; ?>
</div>
<?php endif; ?>

<script>
window.addEventListener('DOMContentLoaded', function() {
  var CSRF    = '<?= csrf_token() ?>';
  var albumId = document.getElementById('albumObras') ? document.getElementById('albumObras').dataset.album : null;

  document.addEventListener('click', function(e) {
    var btn = e.target.closest('.js-quitar');
    if (!btn || !albumId) return;
    var obraId = btn.dataset.obra;
    var col    = document.getElementById('ao-' + obraId);

    Swal.fire({
      title: '¿Quitar obra del álbum?',
      icon: 'question', showCancelButton: true,
      confirmButtonText: 'Sí, quitar', cancelButtonText: 'Cancelar',
      confirmButtonColor: '#e53e3e', cancelButtonColor: '#374151'
    }).then(function(r) {
      if (!r.isConfirmed) return;
      fetch(BASE_URL + '/album/quitar-obra', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded', 'X-Requested-With': 'XMLHttpRequest' },
        body: '_csrf=' + encodeURIComponent(CSRF) + '&album_id=' + albumId + '&obra_id=' + obraId
      }).then(function(res){ return res.json(); }).then(function(d) {
        if (d.ok) {
          if (col) { col.style.transition = 'opacity .3s'; col.style.opacity = '0'; setTimeout(function(){ col.remove(); }, 300); }
          Swal.fire({ toast:true, position:'bottom-end', icon:'success', title:'Obra quitada del álbum', showConfirmButton:false, timer:1800 });
        } else {
          Swal.fire('Error', d.error || 'No se pudo quitar.', 'error');
        }
      }).catch(function(){ Swal.fire('Error de conexión', 'Intenta de nuevo.', 'error'); });
    });
  });
});
</script>
