<?php $pageTitle = 'Subastas'; ?>
<div class="container-xl py-4">
  <div class="d-flex align-items-center justify-content-between mb-4">
    <h1 class="fs-5 font-cinzel mb-0">✦ Subastas Activas</h1>
    <span class="badge-gold"><?= count($subastas ?? []) ?> activas</span>
  </div>

  <?php if(!empty($subastas)): ?>
  <div class="row g-3">
    <?php foreach($subastas as $s): ?>
    <div class="col-md-6 col-xl-4">
      <div class="card-magic overflow-hidden">
        <?php if(!empty($s['imagen_principal'])): ?>
          <img src="<?= media_url('Originales/imagen/Obras_digitales/'.$s['imagen_principal']) ?>"
               class="card-img-top" style="height:200px;object-fit:cover"
               alt="<?= e($s['titulo'] ?? '') ?>"
               onerror="this.src='<?= url('resources/img/placeholder.png') ?>'">
        <?php endif; ?>
        <div class="card-body">
          <h5 class="card-title mb-1"><?= e(truncate($s['titulo'] ?? 'Subasta', 35)) ?></h5>
          <div style="font-size:.78rem;color:var(--pearl-muted);margin-bottom:.75rem">
            <i class="fa fa-user me-1" style="color:var(--purple-mid)"></i><?= e($s['artista'] ?? '') ?>
          </div>

          <div class="row g-2 mb-3">
            <div class="col-6">
              <div style="font-size:.7rem;color:var(--pearl-muted);text-transform:uppercase;letter-spacing:.08em">Precio actual</div>
              <div class="badge-gold mt-1" style="font-size:.9rem">
                $<?= number_format($s['precio_actual'] ?? $s['precio_inicial'] ?? 0, 2) ?>
              </div>
            </div>
            <div class="col-6 text-end">
              <div style="font-size:.7rem;color:var(--pearl-muted);text-transform:uppercase;letter-spacing:.08em">Cierra</div>
              <div style="font-size:.8rem;color:var(--gold);margin-top:.25rem">
                <i class="fa fa-clock me-1"></i>
                <?= format_date($s['fecha_fin'] ?? '') ?>
              </div>
            </div>
          </div>

          <?php if(Auth::check()): ?>
          <form class="pujar-form" data-id="<?= $s['id'] ?>">
            <?= csrf_field() ?>
            <div class="input-group input-group-sm mb-2">
              <span class="input-group-text">$</span>
              <input type="number" class="form-control monto-input"
                     placeholder="Tu oferta"
                     min="<?= ($s['precio_actual'] ?? $s['precio_inicial'] ?? 0) + 1 ?>"
                     step="0.01">
              <button type="submit" class="btn btn-magic" style="padding:.3rem .9rem;font-size:.82rem">
                <i class="fa fa-gavel me-1"></i>Pujar
              </button>
            </div>
            <div class="pujar-msg" style="font-size:.75rem;min-height:18px"></div>
          </form>
          <?php else: ?>
          <a href="<?= url('login') ?>" class="btn btn-outline-magic btn-sm w-100">
            <i class="fa fa-gavel me-1"></i>Iniciar sesión para pujar
          </a>
          <?php endif; ?>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
  <?php else: ?>
  <div class="text-center py-5" style="color:var(--pearl-muted)">
    <i class="fa fa-gavel fa-4x mb-3" style="opacity:.2;display:block"></i>
    <h5 class="font-cinzel">No hay subastas activas</h5>
  </div>
  <?php endif; ?>
</div>

<script>
$(document).on('submit', '.pujar-form', function(e){
  e.preventDefault();
  var form = $(this);
  var id   = form.data('id');
  var monto = form.find('.monto-input').val();
  var csrf  = form.find('input[name="_csrf_token"]').val();
  var msg   = form.find('.pujar-msg');

  fetch(BASE_URL + '/subasta/pujar', {
    method: 'POST',
    headers: {'Content-Type':'application/x-www-form-urlencoded','X-Requested-With':'XMLHttpRequest'},
    body: '_csrf='+encodeURIComponent(csrf)+'&subasta_id='+id+'&monto='+monto
  })
  .then(r => r.json())
  .then(d => {
    if(d.ok){
      msg.html('<span style="color:#4ade80"><i class="fa fa-check me-1"></i>¡Puja registrada!</span>');
      form.find('.monto-input').val('');
    } else {
      msg.html('<span style="color:#f87171"><i class="fa fa-xmark me-1"></i>'+(d.error||'Error al pujar')+'</span>');
    }
  })
  .catch(()=> msg.html('<span style="color:#f87171">Error de conexión</span>'));
});
</script>

