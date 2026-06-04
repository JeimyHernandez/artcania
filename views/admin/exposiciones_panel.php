<?php $pageTitle = 'Gestión de Exposiciones'; ?>

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
  <h2 class="fs-5 font-cinzel mb-0" style="color:var(--gold-light)">
    <i class="fa fa-landmark me-2"></i>Exposiciones
  </h2>
  <button type="button" id="btnNuevaExpo" class="btn btn-magic">
    <i class="fa fa-plus me-1"></i>Nueva exposición
  </button>
</div>

<!-- Modal nueva exposición -->
<div id="modalExpo" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.75);z-index:9999;align-items:center;justify-content:center">
  <div style="background:#16162a;border:1px solid rgba(124,58,237,.35);border-radius:16px;padding:2rem;width:100%;max-width:520px;margin:1rem;max-height:90vh;overflow-y:auto">
    <h5 class="font-cinzel mb-4" style="color:var(--gold-light)"><i class="fa fa-landmark me-2"></i>Nueva exposición</h5>
    <div class="mb-3">
      <label class="form-label" style="font-size:.82rem">Título *</label>
      <input type="text" id="e-titulo" class="form-control" maxlength="200"
             style="background:#1a1a2e;color:var(--pearl);border-color:var(--border)">
    </div>
    <div class="mb-3">
      <label class="form-label" style="font-size:.82rem">Descripción</label>
      <textarea id="e-desc" class="form-control" rows="3"
                style="background:#1a1a2e;color:var(--pearl);border-color:var(--border);resize:none"></textarea>
    </div>
    <div class="row g-3 mb-3">
      <div class="col-6">
        <label class="form-label" style="font-size:.82rem">Tipo</label>
        <select id="e-tipo" class="form-select" style="background:#1a1a2e;color:var(--pearl);border-color:var(--border)">
          <option value="virtual">Virtual</option>
          <option value="presencial">Presencial</option>
          <option value="hibrida">Híbrida</option>
        </select>
      </div>
      <div class="col-6">
        <label class="form-label" style="font-size:.82rem">Fecha inicio</label>
        <input type="date" id="e-inicio" class="form-control"
               style="background:#1a1a2e;color:var(--pearl);border-color:var(--border)">
      </div>
      <div class="col-6">
        <label class="form-label" style="font-size:.82rem">Fecha fin</label>
        <input type="date" id="e-fin" class="form-control"
               style="background:#1a1a2e;color:var(--pearl);border-color:var(--border)">
      </div>
    </div>
    <div class="d-flex gap-2 justify-content-end">
      <button type="button" id="btnCancelarExpo"
              style="background:rgba(248,113,113,.1);color:#f87171;border:1px solid rgba(248,113,113,.3);border-radius:8px;padding:6px 18px;font-size:.83rem;cursor:pointer">
        Cancelar
      </button>
      <button type="button" id="btnGuardarExpo"
              style="background:rgba(124,58,237,.3);color:#a78bfa;border:1px solid rgba(124,58,237,.4);border-radius:8px;padding:6px 18px;font-size:.83rem;cursor:pointer">
        <i class="fa fa-check me-1"></i>Crear
      </button>
    </div>
  </div>
</div>

<div class="card-magic p-0 overflow-hidden">
  <div class="table-responsive">
    <table class="table table-dark table-hover align-middle mb-0" style="font-size:.84rem;color:var(--pearl)">
      <thead>
        <tr style="border-bottom:1px solid var(--border);color:var(--gold-light)">
          <th>#</th><th>Título</th><th>Curador</th><th>Tipo</th>
          <th>Inicio</th><th>Fin</th><th>Estado</th><th>Aprobado por</th><th>Acciones</th>
        </tr>
      </thead>
      <tbody id="tbExposiciones">
      <?php
      $ec = [
        'pendiente'  => ['#fbbf24','rgba(251,191,36,.15)'],
        'aprobada'   => ['#34d399','rgba(52,211,153,.15)'],
        'cancelada'  => ['#f87171','rgba(248,113,113,.15)'],
        'finalizada' => ['#a78bfa','rgba(167,139,250,.15)'],
      ];
      foreach($exposiciones as $x):
        $est = $x['estado'] ?? 'pendiente';
        [$clr,$bg] = $ec[$est] ?? ['#a78bfa','rgba(167,139,250,.15)'];
      ?>
        <tr id="erow-<?= (int)$x['id'] ?>">
          <td style="color:var(--pearl-muted)"><?= (int)$x['id'] ?></td>
          <td style="max-width:180px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap"><?= e($x['titulo'] ?? '—') ?></td>
          <td style="color:var(--teal);font-size:.8rem"><?= e($x['curador_nombre'] ?? '—') ?></td>
          <td style="font-size:.78rem;color:var(--pearl-muted)"><?= e($x['tipo'] ?? '—') ?></td>
          <td style="font-size:.78rem;color:var(--pearl-muted)"><?= $x['fecha_inicio'] ? date('d/m/Y', strtotime($x['fecha_inicio'])) : '—' ?></td>
          <td style="font-size:.78rem;color:var(--pearl-muted)"><?= $x['fecha_fin'] ? date('d/m/Y', strtotime($x['fecha_fin'])) : '—' ?></td>
          <td>
            <span class="e-badge-estado"
                  style="background:<?= $bg ?>;color:<?= $clr ?>;padding:2px 10px;border-radius:20px;font-size:.72rem;font-weight:600">
              <?= ucfirst($est) ?>
            </span>
          </td>
          <td style="font-size:.78rem;color:var(--teal)"><?= e($x['aprobador'] ?? '—') ?></td>
          <td>
            <div class="d-flex gap-1">
              <?php if($est !== 'aprobada' && $est !== 'cancelada' && $est !== 'finalizada'): ?>
                <button type="button" class="btn-expo btn-aprobar"
                        data-id="<?= (int)$x['id'] ?>"
                        title="Aprobar"
                        style="background:rgba(52,211,153,.15);color:#34d399;border:1px solid rgba(52,211,153,.3);border-radius:6px;padding:3px 9px;font-size:.75rem;cursor:pointer">
                  <i class="fa fa-check"></i>
                </button>
              <?php endif; ?>
              <?php if($est !== 'cancelada' && $est !== 'finalizada'): ?>
                <button type="button" class="btn-expo btn-cancelar"
                        data-id="<?= (int)$x['id'] ?>"
                        title="Cancelar"
                        style="background:rgba(248,113,113,.15);color:#f87171;border:1px solid rgba(248,113,113,.3);border-radius:6px;padding:3px 9px;font-size:.75rem;cursor:pointer">
                  <i class="fa fa-ban"></i>
                </button>
              <?php endif; ?>
            </div>
          </td>
        </tr>
      <?php endforeach; ?>
      <?php if(empty($exposiciones)): ?>
        <tr><td colspan="9" class="text-center py-4" style="color:var(--pearl-muted)">Sin exposiciones registradas.</td></tr>
      <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
<p style="color:var(--pearl-muted);font-size:.78rem;margin-top:.5rem">Total: <?= count($exposiciones) ?> exposición(es)</p>

<script>
window.addEventListener('DOMContentLoaded', function() {
  var CSRF = '<?= csrf_token() ?>';
  var estadoColors = {
    pendiente:  ['#fbbf24','rgba(251,191,36,.15)'],
    aprobada:   ['#34d399','rgba(52,211,153,.15)'],
    cancelada:  ['#f87171','rgba(248,113,113,.15)'],
    finalizada: ['#a78bfa','rgba(167,139,250,.15)']
  };

  function actualizarFilaExpo(id, estado, aprobador) {
    var row = document.getElementById('erow-' + id);
    if (!row) return;
    var badge = row.querySelector('.e-badge-estado');
    var c = estadoColors[estado] || estadoColors['pendiente'];
    badge.textContent      = estado.charAt(0).toUpperCase() + estado.slice(1);
    badge.style.color      = c[0];
    badge.style.background = c[1];
    if (aprobador) {
      var cells = row.querySelectorAll('td');
      if (cells[7]) cells[7].textContent = aprobador;
    }
    row.querySelectorAll('.btn-expo').forEach(function(b){ b.remove(); });
  }

  document.addEventListener('click', function(e) {
    var btn = e.target.closest('.btn-aprobar.btn-expo');
    if (btn) {
      var id = btn.dataset.id;
      Swal.fire({
        title: '¿Aprobar exposición #' + id + '?',
        text: 'La exposición quedará pública.',
        icon: 'question', showCancelButton: true,
        confirmButtonText: 'Sí, aprobar', cancelButtonText: 'Cancelar',
        confirmButtonColor: '#7c3aed', cancelButtonColor: '#374151'
      }).then(function(r) {
        if (!r.isConfirmed) return;
        fetch(BASE_URL + '/panel/exposiciones/aprobar', {
          method: 'POST',
          headers: { 'Content-Type': 'application/x-www-form-urlencoded', 'X-Requested-With': 'XMLHttpRequest' },
          body: '_csrf=' + encodeURIComponent(CSRF) + '&id=' + id
        }).then(function(res){ return res.json(); }).then(function(d) {
          if (d.ok) { actualizarFilaExpo(id, 'aprobada', '<?= e(Auth::user()['nombre'] ?? '') ?>'); Swal.fire({ toast:true, position:'bottom-end', icon:'success', title:'Exposición aprobada y pública', showConfirmButton:false, timer:2200 }); }
          else Swal.fire('Error', d.error || 'Error.', 'error');
        }).catch(function(){ Swal.fire('Error de conexión', '', 'error'); });
      });
      return;
    }

    btn = e.target.closest('.btn-cancelar.btn-expo');
    if (btn) {
      var id = btn.dataset.id;
      Swal.fire({
        title: '¿Cancelar exposición #' + id + '?',
        input: 'text', inputLabel: 'Motivo', inputValue: 'Cancelada por gestión',
        icon: 'warning', showCancelButton: true,
        confirmButtonText: 'Cancelar expo', cancelButtonText: 'Volver',
        confirmButtonColor: '#e53e3e', cancelButtonColor: '#374151'
      }).then(function(r) {
        if (!r.isConfirmed) return;
        fetch(BASE_URL + '/panel/exposiciones/cancelar', {
          method: 'POST',
          headers: { 'Content-Type': 'application/x-www-form-urlencoded', 'X-Requested-With': 'XMLHttpRequest' },
          body: '_csrf=' + encodeURIComponent(CSRF) + '&id=' + id + '&motivo=' + encodeURIComponent(r.value || 'Cancelada por gestión')
        }).then(function(res){ return res.json(); }).then(function(d) {
          if (d.ok) { actualizarFilaExpo(id, 'cancelada'); Swal.fire({ toast:true, position:'bottom-end', icon:'info', title:'Exposición cancelada', showConfirmButton:false, timer:2000 }); }
          else Swal.fire('Error', d.error || 'Error.', 'error');
        }).catch(function(){ Swal.fire('Error de conexión', '', 'error'); });
      });
    }
  });

  // ── Modal nueva exposición ────────────────────────────────────────────────
  document.getElementById('btnNuevaExpo').addEventListener('click', function(){
    document.getElementById('modalExpo').style.display = 'flex';
  });
  document.getElementById('btnCancelarExpo').addEventListener('click', function(){
    document.getElementById('modalExpo').style.display = 'none';
  });
  document.getElementById('modalExpo').addEventListener('click', function(e){
    if (e.target === this) this.style.display = 'none';
  });

  document.getElementById('btnGuardarExpo').addEventListener('click', function() {
    var titulo = document.getElementById('e-titulo').value.trim();
    if (!titulo) { Swal.fire('Título requerido', 'El título es obligatorio.', 'warning'); return; }
    var btn = this;
    btn.disabled = true;
    btn.innerHTML = '<i class="fa fa-spinner fa-spin me-1"></i>Creando...';
    fetch(BASE_URL + '/panel/exposiciones/crear', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded', 'X-Requested-With': 'XMLHttpRequest' },
      body: '_csrf='        + encodeURIComponent(CSRF)
          + '&titulo='      + encodeURIComponent(titulo)
          + '&descripcion=' + encodeURIComponent(document.getElementById('e-desc').value)
          + '&tipo='        + encodeURIComponent(document.getElementById('e-tipo').value)
          + '&fecha_inicio='+ encodeURIComponent(document.getElementById('e-inicio').value)
          + '&fecha_fin='   + encodeURIComponent(document.getElementById('e-fin').value)
    }).then(function(res){ return res.json(); }).then(function(d) {
      if (d.ok) {
        document.getElementById('modalExpo').style.display = 'none';
        Swal.fire({ title:'¡Exposición creada!', text:'Estado inicial: Pendiente de aprobación.', icon:'success', confirmButtonText:'Recargar', confirmButtonColor:'#7c3aed' })
            .then(function(){ location.reload(); });
      } else Swal.fire('Error', d.error || 'Error al crear.', 'error');
    }).catch(function(){ Swal.fire('Error de conexión', '', 'error'); })
    .finally(function(){ btn.disabled = false; btn.innerHTML = '<i class="fa fa-check me-1"></i>Crear'; });
  });
});
</script>
