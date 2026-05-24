<?php $pageTitle = 'Crear cuenta'; ?>
<div class="auth-card-wide">
  <div class="text-center mb-4">
    <img src="<?= asset('img/logo_artcadia.png') ?>" alt="Artcadia" class="auth-logo-img" style="width:140px">
    <h1 style="font-family:'Cinzel',serif;color:var(--moon);font-size:2.2rem;letter-spacing:.2em;margin:.6rem 0 0">ARTCADIA</h1>
    <div style="color:var(--lavender);letter-spacing:.25em;font-size:.8rem;margin-top:.3rem">✦ TU MUNDO. TU ARTE. TU LEYENDA. ✦</div>
  </div>

  <form method="POST" action="<?= url('registro') ?>">
    <?= csrf_field() ?>
    <div class="mb-3 position-relative"><i class="fa fa-user position-absolute" style="top:14px;left:16px;color:var(--lavender)"></i>
      <input name="nombre" required class="input-magic" placeholder="Nombre" style="padding-left:46px"></div>
    <div class="mb-3 position-relative"><i class="fa fa-user position-absolute" style="top:14px;left:16px;color:var(--lavender)"></i>
      <input name="usuario" required class="input-magic" placeholder="Usuario" style="padding-left:46px"></div>
    <div class="mb-3 position-relative"><i class="fa fa-envelope position-absolute" style="top:14px;left:16px;color:var(--lavender)"></i>
      <input name="email" type="email" required class="input-magic" placeholder="Correo" style="padding-left:46px"></div>
    <div class="mb-3 position-relative"><i class="fa fa-lock position-absolute" style="top:14px;left:16px;color:var(--lavender)"></i>
      <input name="password" type="password" required class="input-magic" placeholder="Contraseña" style="padding-left:46px;padding-right:42px">
      <i class="fa fa-eye position-absolute" style="top:14px;right:16px;color:var(--lavender);cursor:pointer" onclick="var i=this.previousElementSibling;i.type=i.type=='password'?'text':'password'"></i></div>
    <div class="mb-3 position-relative"><i class="fa fa-lock position-absolute" style="top:14px;left:16px;color:var(--lavender)"></i>
      <input name="password_confirmation" type="password" required class="input-magic" placeholder="Confirmar contraseña" style="padding-left:46px;padding-right:42px">
      <i class="fa fa-eye position-absolute" style="top:14px;right:16px;color:var(--lavender);cursor:pointer" onclick="var i=this.previousElementSibling;i.type=i.type=='password'?'text':'password'"></i></div>

    <div class="mb-3 p-3" style="background:rgba(12,6,30,.5);border:1px solid rgba(212,184,255,.2);border-radius:12px">
      <div style="color:var(--lavender)"><i class="fa fa-shield-halved me-2"></i>Rol</div>
      <div class="d-flex gap-4 mt-2">
        <label style="color:var(--moon)"><input type="radio" name="rol" value="usuario" checked> Usuario</label>
        <label style="color:var(--moon)"><input type="radio" name="rol" value="artista"> Artista</label>
      </div>
    </div>

    <div class="mb-3" style="color:var(--moon)">
      <label><input type="checkbox" required> ✦ Acepto los <a href="#" style="color:var(--magenta)">términos y condiciones</a></label>
    </div>

    <button class="btn-magic btn-magic-solid w-100" style="padding:.9rem;font-size:1.1rem;letter-spacing:.2em">✦ CREAR CUENTA ✦</button>
  </form>

  <div class="text-center mt-4">
    <div style="font-family:'Cinzel',serif;color:var(--gold);letter-spacing:.2em;font-size:.85rem">ÚNETE A ARTCADIA</div>
    <small style="color:rgba(212,184,255,.6);font-style:italic;font-family:'Cormorant Garamond',serif">Donde la imaginación se convierte en eternidad.</small>
    <div class="mt-2"><a href="<?= url('login') ?>" style="color:var(--magenta)">¿Ya tienes cuenta? Inicia sesión</a></div>
  </div>
</div>
