<?php $pageTitle = 'Crear cuenta'; ?>
<div class="text-center mb-3">
  <a href="<?= url('') ?>" class="auth-back-home">
    <i class="fa fa-arrow-left"></i> Volver al inicio
  </a>
</div>
<div class="card-auth" style="max-width:460px">

  <!-- =========================================================
       Encabezado visual del formulario de registro.
       Presenta el logo institucional de Artcania junto
       al nombre de la plataforma y su mensaje principal.
  ========================================================= -->
  <div class="card-header-magic">

      <img
          src="<?= asset('img/logo.png') ?>"
          alt="Logo Artcania"
          class="auth-logo-img"
      >

      <div class="auth-logo-text">ARTCANIA</div>

      <div class="auth-tagline">
          ✧ Tu mundo. Tu arte. Tu leyenda. ✧
      </div>

  </div>

  <div class="card-body">
    <form method="POST" action="<?= url('registro') ?>" novalidate>
      <?= csrf_field() ?>

      <div class="mb-3">
        <div class="input-group">
          <span class="input-group-text"><i class="fa fa-user"></i></span>
          <input type="text" name="nombre" class="form-control"
                 placeholder="Nombre completo" value="<?= old('nombre') ?>" required autofocus>
        </div>
      </div>

      <div class="mb-3">
        <div class="input-group">
          <span class="input-group-text"><i class="fa fa-envelope"></i></span>
          <input type="email" name="email" class="form-control"
                 placeholder="Correo electrónico" value="<?= old('email') ?>" required>
        </div>
      </div>

      <div class="mb-3">
        <div class="input-group">
          <span class="input-group-text"><i class="fa fa-lock"></i></span>
          <input type="password" name="password" id="p1"
                 class="form-control" placeholder="Contraseña" required>
          <button type="button" class="input-group-text toggle-pass"
                  data-target="p1" style="cursor:pointer;border-radius:0 12px 12px 0;border-left:none">
            <i class="fa fa-eye"></i>
          </button>
        </div>
      </div>

      <div class="mb-3">
        <div class="input-group">
          <span class="input-group-text"><i class="fa fa-lock"></i></span>
          <input type="password" name="password_confirm" id="p2"
                 class="form-control" placeholder="Confirmar contraseña" required>
          <button type="button" class="input-group-text toggle-pass"
                  data-target="p2" style="cursor:pointer;border-radius:0 12px 12px 0;border-left:none">
            <i class="fa fa-eye"></i>
          </button>
        </div>
      </div>

      <!-- Rol -->
      <div class="mb-4">
        <div class="input-group align-items-center px-3 py-2"
             style="background:rgba(15,10,30,.7);border:1px solid var(--border);border-radius:12px;gap:1.5rem">
          <i class="fa fa-shield-halved" style="color:var(--purple-mid)"></i>
          <div class="d-flex gap-4">
            <div class="form-check mb-0">
              <input class="form-check-input" type="radio" name="rol" id="rolUser"
                     value="usuario" <?= old('rol','usuario')==='usuario'?'checked':'' ?>>
              <label class="form-check-label" for="rolUser"
                     style="font-size:.88rem;color:var(--pearl)">Usuario</label>
            </div>
            <div class="form-check mb-0">
              <input class="form-check-input" type="radio" name="rol" id="rolArtista"
                     value="artista" <?= old('rol')==='artista'?'checked':'' ?>>
              <label class="form-check-label" for="rolArtista"
                     style="font-size:.88rem;color:var(--pearl)">Artista</label>
            </div>
          </div>
        </div>
      </div>

      <button type="submit" class="btn btn-magic w-100 py-2" style="font-size:.95rem;letter-spacing:.1em;font-family:'Cinzel',serif">
        CREAR CUENTA ✦
      </button>
    </form>

    <div class="text-center mt-3" style="font-size:.85rem;color:var(--pearl-muted)">
      ¿Ya tienes cuenta?
      <a href="<?= url('login') ?>" style="color:var(--purple-mid);font-weight:600;text-decoration:none"> Inicia sesión</a>
    </div>
  </div>
</div>

<script>
document.querySelectorAll('.toggle-pass').forEach(function(btn){
  btn.addEventListener('click', function(){
    var inp = document.getElementById(this.dataset.target);
    var ico = this.querySelector('i');
    if(inp.type==='password'){
      inp.type='text'; ico.classList.replace('fa-eye','fa-eye-slash');
    } else {
      inp.type='password'; ico.classList.replace('fa-eye-slash','fa-eye');
    }
  });
});
</script>
