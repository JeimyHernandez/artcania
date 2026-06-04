<?php $pageTitle = 'Gestión de Roles'; ?>
<div class="admin-page-header mb-4">
  <h4 class="font-cinzel mb-0" style="color:var(--gold-light)">
    <i class="fa fa-shield-halved me-2"></i>Gestión de Roles
  </h4>
</div>

<div class="card-magic p-4">
  <?php if(empty($gestion_roles)): ?>
    <p style="color:var(--pearl-muted);font-size:.9rem">No hay usuarios registrados.</p>
  <?php else: ?>

  <?php
  $rolColors = [
    'admin'   => ['bg'=>'rgba(248,113,113,.15)',  'color'=>'#f87171'],
    'artista' => ['bg'=>'rgba(167,139,250,.15)',  'color'=>'#a78bfa'],
    'curador' => ['bg'=>'rgba(251,191,36,.15)',   'color'=>'#fbbf24'],
    'usuario' => ['bg'=>'rgba(52,211,153,.15)',   'color'=>'#34d399'],
  ];
  ?>

  <div style="overflow-x:auto">
    <table class="table table-dark table-hover" style="font-size:.85rem;color:var(--pearl)">
      <thead>
        <tr style="border-bottom:1px solid var(--border);color:var(--gold-light)">
          <th>#</th>
          <th>Usuario</th>
          <th>Email</th>
          <th>Rol actual</th>
          <th>Cambiar rol</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($gestion_roles as $u): ?>
        <?php
          $rol = $u['rol'] ?? 'usuario';
          $c   = $rolColors[$rol] ?? $rolColors['usuario'];
        ?>
        <tr style="border-bottom:1px solid var(--border)" id="row-<?= (int)$u['id'] ?>">
          <td style="color:var(--pearl-muted)"><?= (int)$u['id'] ?></td>
          <td>
            <div style="display:flex;align-items:center;gap:.5rem">
              <img src="<?= avatar($u['avatar'] ?? '') ?>"
                   style="width:30px;height:30px;border-radius:50%;border:1px solid var(--border-gold);object-fit:cover" alt="">
              <?= e($u['nombre']) ?>
            </div>
          </td>
          <td style="color:var(--pearl-muted);font-size:.8rem"><?= e($u['email']) ?></td>
          <td class="rol-badge-cell">
            <span class="rol-badge" style="background:<?= $c['bg'] ?>;color:<?= $c['color'] ?>;padding:3px 10px;border-radius:20px;font-size:.75rem;font-weight:600">
              <?= ucfirst($rol) ?>
            </span>
          </td>
          <td>
            <div style="display:flex;align-items:center;gap:.4rem">
              <select class="rol-select"
                      data-id="<?= (int)$u['id'] ?>"
                      data-nombre="<?= e(htmlspecialchars($u['nombre'], ENT_QUOTES)) ?>"
                      data-csrf="<?= csrf_token() ?>"
                      style="background:rgba(255,255,255,.06);border:1px solid var(--border);border-radius:8px;color:var(--pearl);padding:4px 8px;font-size:.8rem">
                <?php foreach(['usuario','artista','curador','admin'] as $r): ?>
                  <option value="<?= $r ?>" <?= $rol === $r ? 'selected' : '' ?>
                          style="background:#1a1a2e;color:var(--pearl)">
                    <?= ucfirst($r) ?>
                  </option>
                <?php endforeach; ?>
              </select>
              <button type="button" class="rol-btn"
                      style="background:rgba(124,58,237,.25);color:#a78bfa;border:1px solid rgba(124,58,237,.4);border-radius:8px;padding:4px 12px;font-size:.78rem;cursor:pointer">
                <i class="fa fa-check"></i>
              </button>
            </div>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <p style="color:var(--pearl-muted);font-size:.78rem;margin-top:.5rem">Total: <?= count($gestion_roles) ?> usuario(s)</p>
  <?php endif; ?>
</div>

<script>
// Espera a que jQuery esté disponible (el layout lo carga DESPUÉS del content)
window.addEventListener('DOMContentLoaded', function() {
  var rolColors = {
    'admin':   {bg:'rgba(248,113,113,.15)', color:'#f87171'},
    'artista': {bg:'rgba(167,139,250,.15)', color:'#a78bfa'},
    'curador': {bg:'rgba(251,191,36,.15)',  color:'#fbbf24'},
    'usuario': {bg:'rgba(52,211,153,.15)',  color:'#34d399'}
  };

  document.addEventListener('click', function(e) {
    var btn = e.target.closest('.rol-btn');
    if (!btn) return;

    var select  = btn.parentElement.querySelector('.rol-select');
    var id      = select.dataset.id;
    var nombre  = select.dataset.nombre;
    var csrf    = select.dataset.csrf;
    var nuevoRol = select.value;

    Swal.fire({
      title: '¿Cambiar rol?',
      html: 'Se asignará el rol <strong>' + nuevoRol + '</strong> a <strong>' + nombre + '</strong>.',
      icon: 'question',
      showCancelButton: true,
      confirmButtonText: 'Sí, cambiar',
      cancelButtonText: 'Cancelar',
      confirmButtonColor: '#7c3aed',
      cancelButtonColor: '#374151'
    }).then(function(result) {
      if (!result.isConfirmed) return;

      btn.disabled = true;
      btn.innerHTML = '<i class="fa fa-spinner fa-spin"></i>';

      fetch(BASE_URL + '/admin/cambiar-rol', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
          'X-Requested-With': 'XMLHttpRequest'
        },
        body: '_csrf=' + encodeURIComponent(csrf) + '&id=' + encodeURIComponent(id) + '&rol=' + encodeURIComponent(nuevoRol)
      })
      .then(function(r) { return r.json(); })
      .then(function(res) {
        if (res.ok) {
          var c = rolColors[nuevoRol] || rolColors['usuario'];
          var badge = document.querySelector('#row-' + id + ' .rol-badge');
          if (badge) {
            badge.textContent = nuevoRol.charAt(0).toUpperCase() + nuevoRol.slice(1);
            badge.style.background = c.bg;
            badge.style.color = c.color;
          }
          if (res.new_csrf) select.dataset.csrf = res.new_csrf;

          Swal.fire({
            title: '¡Rol actualizado!',
            html: '<strong>' + nombre + '</strong> ahora tiene el rol <strong>' + nuevoRol + '</strong>.',
            icon: 'success',
            timer: 2500,
            timerProgressBar: true,
            showConfirmButton: false
          });
        } else {
          Swal.fire({
            title: 'Error',
            text: res.error || 'No se pudo cambiar el rol.',
            icon: 'error',
            confirmButtonColor: '#7c3aed'
          });
        }
      })
      .catch(function() {
        Swal.fire({
          title: 'Error de conexión',
          text: 'No se pudo contactar el servidor. Intenta de nuevo.',
          icon: 'error',
          confirmButtonColor: '#7c3aed'
        });
      })
      .finally(function() {
        btn.disabled = false;
        btn.innerHTML = '<i class="fa fa-check"></i>';
      });
    });
  });
});
</script>
