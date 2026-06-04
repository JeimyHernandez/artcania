<?php $pageTitle = 'Gestión de Subastas'; ?>

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
  <h2 class="fs-5 font-cinzel mb-0" style="color:var(--gold-light)">
    <i class="fa fa-gavel me-2"></i>Subastas
  </h2>
</div>

<!-- ══════════════════════════════════════════════════════════
     SECCIÓN 1 — Solicitudes pendientes de artistas
════════════════════════════════════════════════════════════ -->
<?php $pendientes = array_filter($solicitudes ?? [], fn($s) => $s['estado'] === 'pendiente'); ?>

<div class="mb-4">
  <h6 class="font-cinzel mb-3" style="color:var(--teal)">
    <i class="fa fa-hourglass-half me-2"></i>Solicitudes de artistas
    <?php if(count($pendientes) > 0): ?>
      <span style="background:rgba(251,191,36,.2);color:#fbbf24;padding:1px 9px;border-radius:20px;font-size:.72rem;margin-left:.4rem">
        <?= count($pendientes) ?> pendiente<?= count($pendientes) > 1 ? 's' : '' ?>
      </span>
    <?php endif; ?>
  </h6>

  <div class="card-magic p-0 overflow-hidden">
    <div class="table-responsive">
      <table class="table table-dark table-hover align-middle mb-0" style="font-size:.84rem;color:var(--pearl)">
        <thead>
          <tr style="border-bottom:1px solid var(--border);color:var(--gold-light)">
            <th>#</th><th>Artista</th><th>Obra</th><th>Precio inicial</th>
            <th>Fechas</th><th>Nota artista</th><th>Estado</th><th>Acciones</th>
          </tr>
        </thead>
        <tbody id="tbSolicitudes">
          <?php
          $ecSol = [
            'pendiente' => ['#fbbf24','rgba(251,191,36,.15)'],
            'aprobada'  => ['#34d399','rgba(52,211,153,.15)'],
            'rechazada' => ['#f87171','rgba(248,113,113,.15)'],
          ];
          foreach($solicitudes as $s):
            [$clr,$bg] = $ecSol[$s['estado']] ?? ['#a78bfa','rgba(167,139,250,.15)'];
          ?>
          <tr id="solrow-<?= (int)$s['id'] ?>">
            <td style="color:var(--pearl-muted)"><?= (int)$s['id'] ?></td>
            <td style="color:var(--teal);font-size:.8rem"><?= e($s['artista_nombre'] ?? '—') ?></td>
            <td style="max-width:140px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">
              <?= e($s['obra_titulo'] ?? '—') ?>
            </td>
            <td style="color:var(--gold-light)">$<?= number_format($s['precio_inicial'], 2) ?></td>
            <td style="font-size:.77rem;color:var(--pearl-muted)">
              <?= date('d/m/y H:i', strtotime($s['fecha_inicio'])) ?><br>
              → <?= date('d/m/y H:i', strtotime($s['fecha_fin'])) ?>
            </td>
            <td style="font-size:.78rem;color:var(--pearl-muted);max-width:160px">
              <?= $s['nota_artista'] ? e(mb_substr($s['nota_artista'], 0, 80)) . (mb_strlen($s['nota_artista']) > 80 ? '…' : '') : '—' ?>
            </td>
            <td>
              <span class="sol-badge-estado"
                    style="background:<?= $bg ?>;color:<?= $clr ?>;padding:2px 10px;border-radius:20px;font-size:.72rem;font-weight:600">
                <?= ucfirst($s['estado']) ?>
              </span>
            </td>
            <td>
              <?php if($s['estado'] === 'pendiente'): ?>
              <div class="d-flex gap-1">
                <button type="button" class="btn-sol btn-sol-aprobar"
                        data-id="<?= (int)$s['id'] ?>"
                        title="Aprobar solicitud"
                        style="background:rgba(52,211,153,.15);color:#34d399;border:1px solid rgba(52,211,153,.3);border-radius:6px;padding:3px 9px;font-size:.75rem;cursor:pointer">
                  <i class="fa fa-check"></i>
                </button>
                <button type="button" class="btn-sol btn-sol-rechazar"
                        data-id="<?= (int)$s['id'] ?>"
                        title="Rechazar solicitud"
                        style="background:rgba(248,113,113,.15);color:#f87171;border:1px solid rgba(248,113,113,.3);border-radius:6px;padding:3px 9px;font-size:.75rem;cursor:pointer">
                  <i class="fa fa-xmark"></i>
                </button>
              </div>
              <?php else: ?>
                <span style="font-size:.75rem;color:var(--pearl-muted)">
                  <?= $s['revisor_nombre'] ? 'por '.e($s['revisor_nombre']) : '—' ?>
                </span>
              <?php endif; ?>
            </td>
          </tr>
          <?php endforeach; ?>
          <?php if(empty($solicitudes)): ?>
            <tr><td colspan="8" class="text-center py-4" style="color:var(--pearl-muted)">Sin solicitudes registradas.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- ══════════════════════════════════════════════════════════
     SECCIÓN 2 — Subastas activas / programadas
════════════════════════════════════════════════════════════ -->
<div>
  <h6 class="font-cinzel mb-3" style="color:var(--teal)">
    <i class="fa fa-list me-2"></i>Subastas
  </h6>

  <div class="card-magic p-0 overflow-hidden">
    <div class="table-responsive">
      <table class="table table-dark table-hover align-middle mb-0" style="font-size:.84rem;color:var(--pearl)">
        <thead>
          <tr style="border-bottom:1px solid var(--border);color:var(--gold-light)">
            <th>#</th><th>Obra</th><th>Artista</th><th>Inicio</th><th>Fin</th>
            <th>Precio</th><th>Estado</th><th>Aprobado por</th><th>Acciones</th>
          </tr>
        </thead>
        <tbody id="tbSubastas">
          <?php
          $ec = [
            'programada' => ['#fbbf24','rgba(251,191,36,.15)'],
            'activa'     => ['#34d399','rgba(52,211,153,.15)'],
            'finalizada' => ['#a78bfa','rgba(167,139,250,.15)'],
            'cancelada'  => ['#f87171','rgba(248,113,113,.15)'],
          ];
          foreach($subastas as $s):
            [$clr,$bg] = $ec[$s['estado']] ?? ['#a78bfa','rgba(167,139,250,.15)'];
          ?>
          <tr id="srow-<?= (int)$s['id'] ?>">
            <td style="color:var(--pearl-muted)"><?= (int)$s['id'] ?></td>
            <td style="max-width:150px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">
              <?= e($s['obra_titulo'] ?? '—') ?>
            </td>
            <td style="color:var(--pearl-muted);font-size:.8rem"><?= e($s['artista'] ?? '—') ?></td>
            <td style="font-size:.78rem;color:var(--pearl-muted)"><?= $s['fecha_inicio'] ? date('d/m/y H:i', strtotime($s['fecha_inicio'])) : '—' ?></td>
            <td style="font-size:.78rem;color:var(--pearl-muted)"><?= $s['fecha_fin'] ? date('d/m/y H:i', strtotime($s['fecha_fin'])) : '—' ?></td>
            <td style="color:var(--gold-light)">$<?= number_format($s['precio_actual'] ?? 0, 2) ?></td>
            <td>
              <span class="s-badge-estado"
                    style="background:<?= $bg ?>;color:<?= $clr ?>;padding:2px 10px;border-radius:20px;font-size:.72rem;font-weight:600">
                <?= ucfirst($s['estado']) ?>
              </span>
            </td>
            <td style="font-size:.78rem;color:var(--teal)"><?= e($s['aprobador'] ?? '—') ?></td>
            <td>
              <div class="d-flex gap-1">
                <?php if(!in_array($s['estado'], ['activa','finalizada'])): ?>
                  <button type="button" class="btn-panel btn-aprobar"
                          data-id="<?= (int)$s['id'] ?>"
                          title="Activar"
                          style="background:rgba(52,211,153,.15);color:#34d399;border:1px solid rgba(52,211,153,.3);border-radius:6px;padding:3px 9px;font-size:.75rem;cursor:pointer">
                    <i class="fa fa-check"></i>
                  </button>
                <?php endif; ?>
                <?php if(!in_array($s['estado'], ['cancelada','finalizada'])): ?>
                  <button type="button" class="btn-panel btn-cancelar"
                          data-id="<?= (int)$s['id'] ?>"
                          title="Cancelar"
                          style="background:rgba(248,113,113,.15);color:#f87171;border:1px solid rgba(248,113,113,.3);border-radius:6px;padding:3px 9px;font-size:.75rem;cursor:pointer">
                    <i class="fa fa-ban"></i>
                  </button>
                <?php endif; ?>
              </div>
            </td>
          </tr>
          <?php endforeach; ?>
          <?php if(empty($subastas)): ?>
            <tr><td colspan="9" class="text-center py-4" style="color:var(--pearl-muted)">Sin subastas registradas.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
  <p style="color:var(--pearl-muted);font-size:.78rem;margin-top:.5rem">Total: <?= count($subastas) ?> subasta(s)</p>
</div>

<script>
window.addEventListener('DOMContentLoaded', function() {
  var CSRF = '<?= csrf_token() ?>';

  // ── helpers ────────────────────────────────────────────────────────────────
  var estadoColors = {
    programada: ['#fbbf24','rgba(251,191,36,.15)'],
    activa:     ['#34d399','rgba(52,211,153,.15)'],
    finalizada: ['#a78bfa','rgba(167,139,250,.15)'],
    cancelada:  ['#f87171','rgba(248,113,113,.15)']
  };

  function actualizarFilaSubasta(id, estado, aprobador) {
    var row = document.getElementById('srow-' + id);
    if (!row) return;
    var badge = row.querySelector('.s-badge-estado');
    var c = estadoColors[estado] || estadoColors['programada'];
    badge.textContent = estado.charAt(0).toUpperCase() + estado.slice(1);
    badge.style.color = c[0]; badge.style.background = c[1];
    if (aprobador) { var cells = row.querySelectorAll('td'); if (cells[7]) cells[7].textContent = aprobador; }
    row.querySelectorAll('.btn-panel').forEach(function(b){ b.remove(); });
  }

  function actualizarFilaSolicitud(id, estado) {
    var row = document.getElementById('solrow-' + id);
    if (!row) return;
    var colores = { pendiente:['#fbbf24','rgba(251,191,36,.15)'], aprobada:['#34d399','rgba(52,211,153,.15)'], rechazada:['#f87171','rgba(248,113,113,.15)'] };
    var badge = row.querySelector('.sol-badge-estado');
    var c = colores[estado] || colores['pendiente'];
    badge.textContent = estado.charAt(0).toUpperCase() + estado.slice(1);
    badge.style.color = c[0]; badge.style.background = c[1];
    row.querySelectorAll('.btn-sol').forEach(function(b){ b.remove(); });
  }

  // ── Solicitudes: aprobar ───────────────────────────────────────────────────
  document.addEventListener('click', function(e) {
    var btn = e.target.closest('.btn-sol-aprobar');
    if (btn) {
      var id = btn.dataset.id;
      Swal.fire({
        title: '¿Aprobar solicitud #' + id + '?',
        input: 'text', inputLabel: 'Nota para el artista (opcional)',
        inputPlaceholder: 'Ej: Subasta programada para la fecha indicada.',
        icon: 'question', showCancelButton: true,
        confirmButtonText: 'Sí, aprobar', cancelButtonText: 'Cancelar',
        confirmButtonColor: '#7c3aed', cancelButtonColor: '#374151'
      }).then(function(r) {
        if (!r.isConfirmed) return;
        fetch(BASE_URL + '/panel/subastas/solicitud/aprobar', {
          method: 'POST',
          headers: { 'Content-Type': 'application/x-www-form-urlencoded', 'X-Requested-With': 'XMLHttpRequest' },
          body: '_csrf=' + encodeURIComponent(CSRF) + '&id=' + id + '&nota=' + encodeURIComponent(r.value || '')
        }).then(function(res){ return res.json(); }).then(function(d) {
          if (d.ok) {
            actualizarFilaSolicitud(id, 'aprobada');
            Swal.fire({ toast:true, position:'bottom-end', icon:'success', title:'Solicitud aprobada — Subasta #' + d.subasta_id + ' creada', showConfirmButton:false, timer:3000 });
          } else Swal.fire('Error', d.error || 'Error.', 'error');
        }).catch(function(){ Swal.fire('Error de conexión', '', 'error'); });
      });
      return;
    }

    // ── Solicitudes: rechazar ────────────────────────────────────────────────
    btn = e.target.closest('.btn-sol-rechazar');
    if (btn) {
      var id = btn.dataset.id;
      Swal.fire({
        title: 'Rechazar solicitud #' + id,
        input: 'text', inputLabel: 'Motivo del rechazo *',
        inputPlaceholder: 'Ej: Precio muy bajo, fechas en conflicto...',
        icon: 'warning', showCancelButton: true,
        confirmButtonText: 'Rechazar', cancelButtonText: 'Cancelar',
        confirmButtonColor: '#e53e3e', cancelButtonColor: '#374151',
        preConfirm: function(val) {
          if (!val || !val.trim()) { Swal.showValidationMessage('El motivo es obligatorio'); return false; }
          return val;
        }
      }).then(function(r) {
        if (!r.isConfirmed) return;
        fetch(BASE_URL + '/panel/subastas/solicitud/rechazar', {
          method: 'POST',
          headers: { 'Content-Type': 'application/x-www-form-urlencoded', 'X-Requested-With': 'XMLHttpRequest' },
          body: '_csrf=' + encodeURIComponent(CSRF) + '&id=' + id + '&nota=' + encodeURIComponent(r.value)
        }).then(function(res){ return res.json(); }).then(function(d) {
          if (d.ok) {
            actualizarFilaSolicitud(id, 'rechazada');
            Swal.fire({ toast:true, position:'bottom-end', icon:'info', title:'Solicitud rechazada', showConfirmButton:false, timer:2500 });
          } else Swal.fire('Error', d.error || 'Error.', 'error');
        }).catch(function(){ Swal.fire('Error de conexión', '', 'error'); });
      });
      return;
    }

    // ── Subastas: activar ─────────────────────────────────────────────────
    btn = e.target.closest('.btn-aprobar');
    if (btn) {
      var id = btn.dataset.id;
      Swal.fire({
        title: '¿Activar subasta #' + id + '?', icon: 'question', showCancelButton: true,
        confirmButtonText: 'Sí, activar', cancelButtonText: 'Cancelar',
        confirmButtonColor: '#7c3aed', cancelButtonColor: '#374151'
      }).then(function(r) {
        if (!r.isConfirmed) return;
        fetch(BASE_URL + '/panel/subastas/aprobar', {
          method: 'POST',
          headers: { 'Content-Type': 'application/x-www-form-urlencoded', 'X-Requested-With': 'XMLHttpRequest' },
          body: '_csrf=' + encodeURIComponent(CSRF) + '&id=' + id
        }).then(function(res){ return res.json(); }).then(function(d) {
          if (d.ok) { actualizarFilaSubasta(id, 'activa', '<?= e(Auth::user()['nombre'] ?? '') ?>'); Swal.fire({ toast:true, position:'bottom-end', icon:'success', title:'Subasta activada', showConfirmButton:false, timer:2000 }); }
          else Swal.fire('Error', d.error || 'Error.', 'error');
        }).catch(function(){ Swal.fire('Error de conexión', '', 'error'); });
      });
      return;
    }

    // ── Subastas: cancelar ────────────────────────────────────────────────
    btn = e.target.closest('.btn-cancelar');
    if (btn) {
      var id = btn.dataset.id;
      Swal.fire({
        title: '¿Cancelar subasta #' + id + '?',
        input: 'text', inputLabel: 'Motivo de cancelación', inputValue: 'Cancelada por gestión',
        icon: 'warning', showCancelButton: true,
        confirmButtonText: 'Cancelar subasta', cancelButtonText: 'Volver',
        confirmButtonColor: '#e53e3e', cancelButtonColor: '#374151'
      }).then(function(r) {
        if (!r.isConfirmed) return;
        fetch(BASE_URL + '/panel/subastas/cancelar', {
          method: 'POST',
          headers: { 'Content-Type': 'application/x-www-form-urlencoded', 'X-Requested-With': 'XMLHttpRequest' },
          body: '_csrf=' + encodeURIComponent(CSRF) + '&id=' + id + '&motivo=' + encodeURIComponent(r.value || 'Cancelada por gestión')
        }).then(function(res){ return res.json(); }).then(function(d) {
          if (d.ok) { actualizarFilaSubasta(id, 'cancelada'); Swal.fire({ toast:true, position:'bottom-end', icon:'info', title:'Subasta cancelada', showConfirmButton:false, timer:2000 }); }
          else Swal.fire('Error', d.error || 'Error.', 'error');
        }).catch(function(){ Swal.fire('Error de conexión', '', 'error'); });
      });
    }
  });
});
</script>
