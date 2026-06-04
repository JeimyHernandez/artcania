<?php $pageTitle = 'Mis Álbumes'; ?>

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
  <h2 class="fs-5 font-cinzel mb-0" style="color:var(--gold-light)">✦ Mis Álbumes</h2>
  <button type="button" id="btnNuevoAlbum" class="btn btn-magic">
    <i class="fa fa-plus me-1"></i>Nuevo álbum
  </button>
</div>

<?php if(empty($albums)): ?>
  <div class="card-magic p-5 text-center" style="color:var(--pearl-muted)">
    <i class="fa fa-folder-plus fa-3x mb-3" style="opacity:.3;display:block"></i>
    <p>Aún no tienes álbumes. Crea uno para organizar tus obras favoritas.</p>
  </div>
<?php else: ?>
<div class="row g-3" id="listaAlbums">
  <?php foreach($albums as $a): ?>
  <div class="col-md-4 col-sm-6" id="alcard-<?= (int)$a['id'] ?>">
    <div class="card-magic overflow-hidden" style="transition:transform .25s,box-shadow .25s"
         onmouseover="this.style.transform='translateY(-4px)';this.style.boxShadow='0 12px 30px rgba(124,58,237,.3)'"
         onmouseout="this.style.transform='';this.style.boxShadow=''">
      <!-- Portada -->
      <a href="<?= url('album/'.$a['id']) ?>" style="display:block;aspect-ratio:4/3;overflow:hidden;background:rgba(124,58,237,.08)">
        <?php if(!empty($a['preview'])): ?>
          <img src="<?= media_url('Originales/imagen/Obras_digitales/'.$a['preview']) ?>"
               style="width:100%;height:100%;object-fit:cover;transition:transform .5s"
               alt=""
               onmouseover="this.style.transform='scale(1.06)'"
               onmouseout="this.style.transform=''"
               onerror="this.parentElement.innerHTML='<div style=\'height:100%;display:flex;align-items:center;justify-content:center;font-size:2.5rem\'>🗂️</div>'">
        <?php else: ?>
          <div style="height:100%;display:flex;align-items:center;justify-content:center;font-size:2.5rem;color:rgba(124,58,237,.4)">🗂️</div>
        <?php endif; ?>
      </a>
      <!-- Info -->
      <div class="p-3 d-flex align-items-center justify-content-between gap-2">
        <div style="overflow:hidden">
          <div style="font-weight:600;font-size:.9rem;color:var(--pearl);white-space:nowrap;overflow:hidden;text-overflow:ellipsis">
            <?= e($a['nombre']) ?>
          </div>
          <div style="font-size:.72rem;color:var(--pearl-muted)">
            <?= (int)$a['total_obras'] ?> obras · <?= $a['publico'] ? 'Público' : 'Privado' ?>
          </div>
        </div>
        <div class="dropdown flex-shrink-0">
          <button type="button" class="btn btn-sm btn-outline-magic dropdown-toggle" data-bs-toggle="dropdown"
                  style="padding:4px 8px;font-size:.75rem">
            <i class="fa fa-ellipsis-v"></i>
          </button>
          <ul class="dropdown-menu dropdown-menu-end" style="background:#16162a;border:1px solid var(--border)">
            <li>
              <a class="dropdown-item js-album-edit" href="#"
                 style="color:var(--pearl);font-size:.83rem"
                 data-id="<?= (int)$a['id'] ?>"
                 data-nombre="<?= e(htmlspecialchars($a['nombre'], ENT_QUOTES)) ?>"
                 data-descripcion="<?= e(htmlspecialchars($a['descripcion'] ?? '', ENT_QUOTES)) ?>"
                 data-publico="<?= (int)$a['publico'] ?>">
                <i class="fa fa-pen me-2"></i>Editar
              </a>
            </li>
            <li>
              <a class="dropdown-item js-album-del text-danger" href="#"
                 style="font-size:.83rem"
                 data-id="<?= (int)$a['id'] ?>">
                <i class="fa fa-trash me-2"></i>Eliminar
              </a>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>
  <?php endforeach; ?>
</div>
<?php endif; ?>

<script>
window.addEventListener('DOMContentLoaded', function() {
  var CSRF = '<?= csrf_token() ?>';

  function toast(msg, ok) {
    Swal.fire({
      toast: true, position: 'bottom-end', icon: ok === false ? 'error' : 'success',
      title: msg, showConfirmButton: false, timer: 2200, timerProgressBar: true
    });
  }

  // ── Nuevo álbum ───────────────────────────────────────────────────────────
  document.getElementById('btnNuevoAlbum').addEventListener('click', function() {
    Swal.fire({
      title: 'Nuevo álbum',
      html:
        '<input id="swal-nombre" class="swal2-input" placeholder="Nombre del álbum" maxlength="120">' +
        '<input id="swal-desc"   class="swal2-input" placeholder="Descripción (opcional)">' +
        '<label style="display:flex;align-items:center;gap:8px;margin-top:10px;font-size:.85rem;color:#b8b0d4">' +
          '<input type="checkbox" id="swal-pub" checked> Visible en mi perfil' +
        '</label>',
      showCancelButton: true,
      confirmButtonText: 'Crear', cancelButtonText: 'Cancelar',
      confirmButtonColor: '#7c3aed', cancelButtonColor: '#374151',
      preConfirm: function() {
        var n = document.getElementById('swal-nombre').value.trim();
        if (!n) { Swal.showValidationMessage('El nombre es obligatorio'); return false; }
        return {
          nombre:      n,
          descripcion: document.getElementById('swal-desc').value.trim(),
          publico:     document.getElementById('swal-pub').checked ? 1 : 0
        };
      }
    }).then(function(r) {
      if (!r.isConfirmed) return;
      fetch(BASE_URL + '/album/crear', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded', 'X-Requested-With': 'XMLHttpRequest' },
        body: '_csrf=' + encodeURIComponent(CSRF)
          + '&nombre='      + encodeURIComponent(r.value.nombre)
          + '&descripcion=' + encodeURIComponent(r.value.descripcion)
          + '&publico='     + r.value.publico
      }).then(function(res){ return res.json(); }).then(function(d) {
        if (d.ok) { toast('¡Álbum creado!'); setTimeout(function(){ location.reload(); }, 800); }
        else toast(d.error || 'Error al crear.', false);
      }).catch(function(){ toast('Error de conexión.', false); });
    });
  });

  // ── Editar álbum ──────────────────────────────────────────────────────────
  document.addEventListener('click', function(e) {
    var btn = e.target.closest('.js-album-edit');
    if (btn) {
      e.preventDefault();
      var id   = btn.dataset.id;
      var nom  = btn.dataset.nombre;
      var desc = btn.dataset.descripcion;
      var pub  = btn.dataset.publico === '1';

      Swal.fire({
        title: 'Editar álbum',
        html:
          '<input id="e-nombre" class="swal2-input" value="' + nom.replace(/"/g,'&quot;') + '" maxlength="120">' +
          '<input id="e-desc" class="swal2-input" value="' + desc.replace(/"/g,'&quot;') + '">' +
          '<label style="display:flex;align-items:center;gap:8px;margin-top:10px;font-size:.85rem;color:#b8b0d4">' +
            '<input type="checkbox" id="e-pub"' + (pub ? ' checked' : '') + '> Visible en mi perfil' +
          '</label>',
        showCancelButton: true,
        confirmButtonText: 'Guardar', cancelButtonText: 'Cancelar',
        confirmButtonColor: '#7c3aed', cancelButtonColor: '#374151',
        preConfirm: function() {
          var n = document.getElementById('e-nombre').value.trim();
          if (!n) { Swal.showValidationMessage('El nombre es obligatorio'); return false; }
          return {
            nombre:      n,
            descripcion: document.getElementById('e-desc').value.trim(),
            publico:     document.getElementById('e-pub').checked ? 1 : 0
          };
        }
      }).then(function(r) {
        if (!r.isConfirmed) return;
        fetch(BASE_URL + '/album/renombrar', {
          method: 'POST',
          headers: { 'Content-Type': 'application/x-www-form-urlencoded', 'X-Requested-With': 'XMLHttpRequest' },
          body: '_csrf=' + encodeURIComponent(CSRF)
            + '&id='         + id
            + '&nombre='      + encodeURIComponent(r.value.nombre)
            + '&descripcion=' + encodeURIComponent(r.value.descripcion)
            + '&publico='     + r.value.publico
        }).then(function(res){ return res.json(); }).then(function(d) {
          if (d.ok) { toast('¡Actualizado!'); setTimeout(function(){ location.reload(); }, 600); }
          else toast(d.error || 'Error.', false);
        });
      });
      return;
    }

    // ── Eliminar álbum ────────────────────────────────────────────────────
    btn = e.target.closest('.js-album-del');
    if (btn) {
      e.preventDefault();
      var id = btn.dataset.id;
      Swal.fire({
        title: '¿Eliminar álbum?',
        text: 'Se elimina el álbum y sus referencias. Las obras no se borran.',
        icon: 'warning', showCancelButton: true,
        confirmButtonText: 'Sí, eliminar', cancelButtonText: 'Cancelar',
        confirmButtonColor: '#e53e3e', cancelButtonColor: '#374151'
      }).then(function(r) {
        if (!r.isConfirmed) return;
        fetch(BASE_URL + '/album/eliminar', {
          method: 'POST',
          headers: { 'Content-Type': 'application/x-www-form-urlencoded', 'X-Requested-With': 'XMLHttpRequest' },
          body: '_csrf=' + encodeURIComponent(CSRF) + '&id=' + id
        }).then(function(res){ return res.json(); }).then(function(d) {
          if (d.ok) {
            var card = document.getElementById('alcard-' + id);
            if (card) card.remove();
            toast('¡Eliminado!');
          } else toast(d.error || 'Error.', false);
        });
      });
    }
  });
});
</script>
