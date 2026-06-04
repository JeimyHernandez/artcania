<?php
/** @var int $artistaId */
/** @var string $artistaNombre */
?>
<div class="card shadow-sm">
  <div class="card-header fw-bold"><i class="fa fa-envelope me-2"></i>Contactar a <?= e($artistaNombre??'Artista') ?></div>
  <div class="card-body">
    <form id="formContactoArtista">
      <input type="hidden" name="artista_id" value="<?= e($artistaId??0) ?>">
      <div class="mb-3">
        <label class="form-label fw-semibold">Asunto *</label>
        <input type="text" name="asunto" class="form-control" required minlength="3" maxlength="200" placeholder="Consulta sobre obra, comisión...">
      </div>
      <div class="mb-3">
        <label class="form-label fw-semibold">Mensaje *</label>
        <textarea name="mensaje" class="form-control" rows="4" required minlength="10" placeholder="Escribe tu mensaje..."></textarea>
      </div>
      <div id="contactoMsg"></div>
      <?php if(Auth::check()): ?>
      <button type="button" class="btn btn-primary w-100" onclick="enviarContacto()">
        <i class="fa fa-paper-plane me-2"></i>Enviar Mensaje
      </button>
      <?php else: ?>
      <a href="<?= url('login') ?>" class="btn btn-outline-primary w-100"><i class="fa fa-sign-in-alt me-2"></i>Inicia sesión para contactar</a>
      <?php endif; ?>
    </form>
  </div>
</div>
<script>
function enviarContacto(){
  const form = document.getElementById('formContactoArtista');
  const data = new FormData(form);
  data.append('_csrf', '<?= csrf_token() ?>');
  const body = new URLSearchParams(data).toString();
  fetch('<?= url('contacto/artista') ?>', {
    method:'POST',
    headers:{'Content-Type':'application/x-www-form-urlencoded','X-Requested-With':'XMLHttpRequest'},
    body
  }).then(r=>r.json()).then(d=>{
    const el = document.getElementById('contactoMsg');
    if(d.ok){ el.innerHTML='<div class="alert alert-success">¡Mensaje enviado!</div>'; form.reset(); }
    else { el.innerHTML='<div class="alert alert-danger">Error al enviar. Intenta de nuevo.</div>'; }
  }).catch(()=>{ document.getElementById('contactoMsg').innerHTML='<div class="alert alert-danger">Error de conexión.</div>'; });
}
</script>
