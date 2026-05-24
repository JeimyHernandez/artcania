<?php $pageTitle = 'Ediciones Limitadas';
$ediciones = $ediciones ?? [];
?>
<div class="container py-4">
  <div class="cosmic-header">
    <div class="ornament">✦</div>
    <h1 class="cosmic-title">⟡ EDICIONES LIMITADAS ⟡</h1>
    <p class="cosmic-subtitle">Obras únicas. Ediciones exclusivas. Legado eterno.</p>
  </div>

  <div class="row g-4 justify-content-center">
    <?php if(empty($ediciones)): $ediciones = [
      ['titulo'=>'Susurros del Eterno','autor'=>'ARTCADIA','precio'=>'1,250','edicion'=>'3/10'],
      ['titulo'=>'Portal Celestial','autor'=>'ARTCADIA','precio'=>'1,350','edicion'=>'3/10'],
      ['titulo'=>'Guardián de Orión','autor'=>'ARTCADIA','precio'=>'1,200','edicion'=>'3/10'],
    ]; endif; ?>
    <?php foreach($ediciones as $i=>$e): ?>
    <div class="col-md-6 col-lg-4">
      <div class="edition-card">
        <div class="edition-banner">EDICIÓN<b><?= e($e['edicion']??'1/10') ?></b></div>
        <div style="aspect-ratio:1/1;border-radius:14px;background:linear-gradient(<?= ($i*70)%360 ?>deg,#1a0a40,#7a3df0,#c77dff);margin:1rem 0;border:1px solid rgba(255,213,138,.3)"></div>
        <h4 style="font-family:'Cinzel',serif;color:var(--gold);font-size:1.2rem;letter-spacing:.08em;margin:0"><?= strtoupper(e($e['titulo']??'Obra')) ?></h4>
        <p style="color:rgba(212,184,255,.7);font-style:italic;font-family:'Cormorant Garamond',serif;margin:.2rem 0 1rem">por <?= e($e['autor']??'ARTCADIA') ?></p>
        <hr style="border-color:rgba(255,213,138,.2);margin:.5rem 0 1rem">
        <div class="d-flex justify-content-around align-items-center mb-3">
          <div><span style="color:var(--magenta)">✦</span> <b style="color:var(--moon);font-family:'Cinzel',serif;font-size:1.3rem"><?= e($e['precio']??'0') ?></b></div>
          <div class="text-start" style="color:rgba(212,184,255,.7);font-size:.8rem"><i class="fa fa-certificate" style="color:var(--gold)"></i> Certificado<br>de Autenticidad</div>
        </div>
        <a href="#" class="btn-magic btn-magic-gold w-100">✦ Adquirir Edición ✦</a>
      </div>
    </div>
    <?php endforeach; ?>
  </div>

  <div class="glass-panel mt-5">
    <div class="row text-center g-4">
      <div class="col-md-3"><i class="fa fa-shield-halved fa-2x mb-2" style="color:var(--gold)"></i>
        <h6 style="font-family:'Cinzel',serif;color:var(--gold)">Ediciones Limitadas</h6>
        <small style="color:rgba(212,184,255,.7)">Solo unas pocas ediciones existen en el multiverso.</small></div>
      <div class="col-md-3"><i class="fa fa-gem fa-2x mb-2" style="color:var(--magenta)"></i>
        <h6 style="font-family:'Cinzel',serif;color:var(--gold)">Valor coleccionable</h6>
        <small style="color:rgba(212,184,255,.7)">Arte exclusivo que aumenta su valor con el tiempo.</small></div>
      <div class="col-md-3"><i class="fa fa-scroll fa-2x mb-2" style="color:var(--gold)"></i>
        <h6 style="font-family:'Cinzel',serif;color:var(--gold)">Certificado Auténtico</h6>
        <small style="color:rgba(212,184,255,.7)">Cada edición incluye un certificado verificable en blockchain.</small></div>
      <div class="col-md-3"><i class="fa fa-landmark fa-2x mb-2" style="color:var(--gold)"></i>
        <h6 style="font-family:'Cinzel',serif;color:var(--gold)">Legado eterno</h6>
        <small style="color:rgba(212,184,255,.7)">Tu colección, tu legado. Para siempre.</small></div>
    </div>
  </div>
</div>
