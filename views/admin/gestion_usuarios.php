<?php $pageTitle = 'Gestión de Usuarios'; ?>
<div class="admin-page-header">
  <h2><i class="fa fa-users me-2"></i>Usuarios</h2>
  <span class="badge-gold"><?= count($users ?? []) ?> registrados</span>
</div>

<div class="card-magic p-0 overflow-hidden">
  <div class="p-3 border-bottom" style="border-color:var(--border)!important">
    <input type="text" id="userSearch" class="form-control" placeholder="Buscar por nombre o email...">
  </div>
  <div class="table-responsive">
    <table class="table table-magic mb-0">
      <thead>
        <tr>
          <th>Usuario</th><th>Email</th><th>Rol</th>
          <th>Verificado</th><th>Activo</th><th>Acciones</th>
        </tr>
      </thead>
      <tbody id="userTable">
      <?php foreach($users ?? [] as $u): ?>
        <tr data-search="<?= strtolower(e($u['nombre']).' '.e($u['email'])) ?>" id="urow-<?= (int)$u['id'] ?>">
          <td>
            <div class="d-flex align-items-center gap-2">
              <img src="<?= avatar($u['avatar'] ?? '') ?>"
                   class="rounded-circle" style="width:32px;height:32px;object-fit:cover;border:1px solid var(--border)" alt="">
              <span style="color:var(--pearl);font-size:.875rem"><?= e($u['nombre']) ?></span>
            </div>
          </td>
          <td style="font-size:.8rem"><?= e($u['email']) ?></td>
          <td>
            <?php
            $rolColors = ['admin'=>'badge-gold','artista'=>'badge-magic','curador'=>'badge-teal','usuario'=>'badge-magic'];
            ?>
            <span class="rol-badge-cell <?= $rolColors[$u['rol']] ?? 'badge-magic' ?>">
              <?= ucfirst($u['rol'] ?? 'usuario') ?>
            </span>
          </td>
          <td>
            <?php if($u['verificado']): ?>
              <i class="fa fa-circle-check" style="color:#4ade80"></i>
            <?php else: ?>
              <form method="POST" action="<?= url('admin/verificar-usuario') ?>" style="display:inline" class="form-verificar">
                <?= csrf_field() ?>
                <input type="hidden" name="id" value="<?= $u['id'] ?>">
                <button type="button" class="btn-approve btn-verificar" style="padding:.2rem .6rem;font-size:.7rem"
                        data-nombre="<?= e(htmlspecialchars($u['nombre'], ENT_QUOTES)) ?>">
                  <i class="fa fa-user-check me-1"></i>Verificar
                </button>
              </form>
            <?php endif; ?>
          </td>
          <td>
            <div class="form-check form-switch mb-0">
              <input class="form-check-input toggle-activo" type="checkbox"
                     data-id="<?= $u['id'] ?>"
                     data-nombre="<?= e(htmlspecialchars($u['nombre'], ENT_QUOTES)) ?>"
                     data-csrf="<?= csrf_token() ?>"
                     <?= $u['activo'] ? 'checked' : '' ?>
                     style="cursor:pointer">
            </div>
          </td>
          <td>
            <div class="d-flex align-items-center gap-1">
              <select class="rol-select-usuarios form-select form-select-sm"
                      data-id="<?= (int)$u['id'] ?>"
                      data-nombre="<?= e(htmlspecialchars($u['nombre'], ENT_QUOTES)) ?>"
                      data-csrf="<?= csrf_token() ?>"
                      style="width:120px">
                <?php foreach(['usuario','artista','curador','admin'] as $r): ?>
                  <option value="<?= $r ?>" <?= ($u['rol'] ?? 'usuario') === $r ? 'selected' : '' ?>><?= ucfirst($r) ?></option>
                <?php endforeach; ?>
              </select>
              <button type="button" class="btn btn-sm btn-magic btn-cambiar-rol-usuario" style="padding:.25rem .6rem">✓</button>
            </div>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<script>
window.addEventListener('DOMContentLoaded', function() {

  // ── Buscador ────────────────────────────────────────────────────────────────
  document.getElementById('userSearch').addEventListener('input', function() {
    var q = this.value.toLowerCase();
    document.querySelectorAll('#userTable tr').forEach(function(row) {
      row.style.display = (!q || row.dataset.search.includes(q)) ? '' : 'none';
    });
  });

  // ── Toggle activo/inactivo ──────────────────────────────────────────────────
  document.addEventListener('change', function(e) {
    var cb = e.target.closest('.toggle-activo');
    if (!cb) return;

    var id     = cb.dataset.id;
    var nombre = cb.dataset.nombre;
    var activo = cb.checked ? 1 : 0;
    var csrf   = cb.dataset.csrf;
    var accion = activo ? 'activar' : 'desactivar';

    Swal.fire({
      title: '¿' + accion.charAt(0).toUpperCase() + accion.slice(1) + ' usuario?',
      html: 'Se va a <strong>' + accion + '</strong> la cuenta de <strong>' + nombre + '</strong>.',
      icon: 'question',
      showCancelButton: true,
      confirmButtonText: 'Sí, ' + accion,
      cancelButtonText: 'Cancelar',
      confirmButtonColor: '#7c3aed',
      cancelButtonColor: '#374151'
    }).then(function(result) {
      if (!result.isConfirmed) { cb.checked = !cb.checked; return; }

      fetch(BASE_URL + '/admin/toggle-usuario', {
        method: 'POST',
        headers: {'Content-Type':'application/x-www-form-urlencoded','X-Requested-With':'XMLHttpRequest'},
        body: '_csrf=' + encodeURIComponent(csrf) + '&id=' + id + '&activo=' + activo
      })
      .then(function(r) { return r.json(); })
      .then(function(d) {
        if (!d.ok) {
          cb.checked = !cb.checked;
          Swal.fire({ title:'Error', text: d.error || 'No se pudo cambiar el estado.', icon:'error', confirmButtonColor:'#7c3aed' });
        } else {
          Swal.fire({ title:'¡Listo!', text: nombre + ' ha sido ' + (activo ? 'activado' : 'desactivado') + '.', icon:'success', timer:2000, timerProgressBar:true, showConfirmButton:false });
        }
      })
      .catch(function() {
        cb.checked = !cb.checked;
        Swal.fire({ title:'Error de conexión', text:'Intenta de nuevo.', icon:'error', confirmButtonColor:'#7c3aed' });
      });
    });
  });

  // ── opcionde cambiar el rol ─────────────────────────────────────────────────────────────
  document.addEventListener('click', function(e) {
    var btn = e.target.closest('.btn-cambiar-rol-usuario');
    if (!btn) return;

    var select   = btn.parentElement.querySelector('.rol-select-usuarios');
    var id       = select.dataset.id;
    var nombre   = select.dataset.nombre;
    var csrf     = select.dataset.csrf;
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
        headers: {'Content-Type':'application/x-www-form-urlencoded','X-Requested-With':'XMLHttpRequest'},
        body: '_csrf=' + encodeURIComponent(csrf) + '&id=' + encodeURIComponent(id) + '&rol=' + encodeURIComponent(nuevoRol)
      })
      .then(function(r) { return r.json(); })
      .then(function(res) {
        if (res.ok) {
          var badge = document.querySelector('#urow-' + id + ' .rol-badge-cell');
          if (badge) badge.textContent = nuevoRol.charAt(0).toUpperCase() + nuevoRol.slice(1);
          if (res.new_csrf) select.dataset.csrf = res.new_csrf;

          Swal.fire({ title:'¡Rol actualizado!', html:'<strong>' + nombre + '</strong> ahora tiene el rol <strong>' + nuevoRol + '</strong>.', icon:'success', timer:2500, timerProgressBar:true, showConfirmButton:false });
        } else {
          Swal.fire({ title:'Error', text: res.error || 'No se pudo cambiar el rol.', icon:'error', confirmButtonColor:'#7c3aed' });
        }
      })
      .catch(function() {
        Swal.fire({ title:'Error de conexión', text:'Intenta de nuevo.', icon:'error', confirmButtonColor:'#7c3aed' });
      })
      .finally(function() {
        btn.disabled = false;
        btn.innerHTML = '✓';
      });
    });
  });

  // ── Verificar usuario ───────────────────────────────────────────────────────
  document.addEventListener('click', function(e) {
    var btn = e.target.closest('.btn-verificar');
    if (!btn) return;

    var form   = btn.closest('form');
    var nombre = btn.dataset.nombre;

    Swal.fire({
      title: '¿Verificar usuario?',
      html: 'Se marcará como verificada la cuenta de <strong>' + nombre + '</strong>.',
      icon: 'question',
      showCancelButton: true,
      confirmButtonText: 'Sí, verificar',
      cancelButtonText: 'Cancelar',
      confirmButtonColor: '#7c3aed',
      cancelButtonColor: '#374151'
    }).then(function(result) {
      if (result.isConfirmed) form.submit();
    });
  });

});
</script>
