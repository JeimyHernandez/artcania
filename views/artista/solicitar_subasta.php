<?php $pageTitle = 'Mis Subastas'; ?>

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
  <h2 class="fs-5 font-cinzel mb-0" style="color:var(--gold-light)">
    <i class="fa fa-gavel me-2"></i>Mis Subastas
  </h2>
  <button type="button" id="btnNuevaSolicitud" class="btn btn-magic">
    <i class="fa fa-plus me-1"></i>Solicitar subasta
  </button>
</div>

<!-- ── Flash messages ─────────────────────────────────────────────────────── -->
<?php if($flash_success ?? ''): ?>
  <div class="alert-success-magic mb-3 py-2 px-3">
    <i class="fa fa-circle-check me-2"></i><?= e($flash_success) ?>
  </div>
<?php endif; ?>
<?php if($flash_error ?? ''): ?>
  <div class="alert-danger-magic mb-3 py-2 px-3">
    <i class="fa fa-circle-exclamation me-2"></i><?= e($flash_error) ?>
  </div>
<?php endif; ?>

<!-- ── Modal solicitar subasta ────────────────────────────────────────────── -->
<div id="modalSolicitud" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.75);z-index:9999;align-items:center;justify-content:center">
  <div style="background:#16162a;border:1px solid rgba(124,58,237,.35);border-radius:16px;padding:2rem;width:100%;max-width:520px;margin:1rem;max-height:90vh;overflow-y:auto">
    <h5 class="font-cinzel mb-4" style="color:var(--gold-light)">
      <i class="fa fa-gavel me-2"></i>Solicitar subasta
    </h5>

    <form method="POST" action="<?= url('artista/subastas/solicitar') ?>">
      <?= csrf_field() ?>

      <div class="mb-3">
        <label class="form-label" style="font-size:.82rem">Obra *</label>
        <?php if(empty($obras)): ?>
          <div style="color:var(--pearl-muted);font-size:.83rem;padding:.5rem 0">
            <i class="fa fa-circle-info me-1"></i>
            No tienes obras aprobadas disponibles para subasta.
          </div>
        <?php else: ?>
          <select name="obra_id" class="form-select" required
                  style="background:#1a1a2e;color:var(--pearl);border-color:var(--border)">
            <option value="">— Selecciona una obra —</option>
            <?php foreach($obras as $o): ?>
              <option value="<?= (int)$o['id'] ?>"><?= e($o['titulo']) ?></option>
            <?php endforeach; ?>
          </select>
        <?php endif; ?>
      </div>

      <div class="row g-3 mb-3">
        <div class="col-6">
          <label class="form-label" style="font-size:.82rem">Precio inicial ($) *</label>
          <input type="number" name="precio_inicial" class="form-control" min="1" step="0.01" required
                 style="background:#1a1a2e;color:var(--pearl);border-color:var(--border)">
        </div>
        <div class="col-6">
          <label class="form-label" style="font-size:.82rem">Precio reserva ($)</label>
          <input type="number" name="precio_reserva" class="form-control" min="0" step="0.01"
                 style="background:#1a1a2e;color:var(--pearl);border-color:var(--border)">
        </div>
        <div class="col-6">
          <label class="form-label" style="font-size:.82rem">Incremento mín. ($)</label>
          <input type="number" name="incremento_min" class="form-control" min="0" step="0.01" value="1.00"
                 style="background:#1a1a2e;color:var(--pearl);border-color:var(--border)">
        </div>
        <div class="col-6">
          <label class="form-label" style="font-size:.82rem">Fecha inicio *</label>
          <input type="datetime-local" name="fecha_inicio" class="form-control" required
                 style="background:#1a1a2e;color:var(--pearl);border-color:var(--border)">
        </div>
        <div class="col-12">
          <label class="form-label" style="font-size:.82rem">Fecha fin *</label>
          <input type="datetime-local" name="fecha_fin" class="form-control" required
                 style="background:#1a1a2e;color:var(--pearl);border-color:var(--border)">
        </div>
      </div>

      <div class="mb-4">
        <label class="form-label" style="font-size:.82rem">Nota para el curador (opcional)</label>
        <textarea name="nota_artista" class="form-control" rows="2"
                  placeholder="¿Por qué quieres subastar esta obra? Contexto, urgencia, etc."
                  style="background:#1a1a2e;color:var(--pearl);border-color:var(--border);resize:none"></textarea>
      </div>

      <div class="d-flex gap-2 justify-content-end">
        <button type="button" id="btnCerrarModal"
                style="background:rgba(248,113,113,.1);color:#f87171;border:1px solid rgba(248,113,113,.3);border-radius:8px;padding:6px 18px;font-size:.83rem;cursor:pointer">
          Cancelar
        </button>
        <button type="submit" <?= empty($obras) ? 'disabled' : '' ?>
                style="background:rgba(124,58,237,.3);color:#a78bfa;border:1px solid rgba(124,58,237,.4);border-radius:8px;padding:6px 18px;font-size:.83rem;cursor:pointer">
          <i class="fa fa-paper-plane me-1"></i>Enviar solicitud
        </button>
      </div>
    </form>
  </div>
</div>

<!-- ── Tabla de mis solicitudes ───────────────────────────────────────────── -->
<div class="card-magic p-0 overflow-hidden">
  <div class="table-responsive">
    <table class="table table-dark table-hover align-middle mb-0" style="font-size:.84rem;color:var(--pearl)">
      <thead>
        <tr style="border-bottom:1px solid var(--border);color:var(--gold-light)">
          <th>#</th>
          <th>Obra</th>
          <th>Precio inicial</th>
          <th>Fechas</th>
          <th>Estado</th>
          <th>Nota del revisor</th>
          <th>Enviada</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $estadoStyle = [
          'pendiente' => ['#fbbf24','rgba(251,191,36,.15)'],
          'aprobada'  => ['#34d399','rgba(52,211,153,.15)'],
          'rechazada' => ['#f87171','rgba(248,113,113,.15)'],
        ];
        foreach($solicitudes as $s):
          [$clr,$bg] = $estadoStyle[$s['estado']] ?? ['#a78bfa','rgba(167,139,250,.15)'];
        ?>
        <tr>
          <td style="color:var(--pearl-muted)"><?= (int)$s['id'] ?></td>
          <td style="max-width:160px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">
            <?= e($s['obra_titulo'] ?? '—') ?>
          </td>
          <td style="color:var(--gold-light)">$<?= number_format($s['precio_inicial'], 2) ?></td>
          <td style="font-size:.77rem;color:var(--pearl-muted)">
            <?= date('d/m/y H:i', strtotime($s['fecha_inicio'])) ?><br>
            → <?= date('d/m/y H:i', strtotime($s['fecha_fin'])) ?>
          </td>
          <td>
            <span style="background:<?= $bg ?>;color:<?= $clr ?>;padding:2px 10px;border-radius:20px;font-size:.72rem;font-weight:600">
              <?= ucfirst($s['estado']) ?>
            </span>
          </td>
          <td style="font-size:.78rem;color:var(--pearl-muted);max-width:180px">
            <?= $s['nota_revision'] ? e($s['nota_revision']) : '—' ?>
          </td>
          <td style="font-size:.77rem;color:var(--pearl-muted)">
            <?= date('d/m/y H:i', strtotime($s['creado_en'])) ?>
          </td>
        </tr>
        <?php endforeach; ?>
        <?php if(empty($solicitudes)): ?>
          <tr>
            <td colspan="7" class="text-center py-4" style="color:var(--pearl-muted)">
              <i class="fa fa-gavel fa-2x mb-2" style="opacity:.3;display:block"></i>
              No has enviado ninguna solicitud aún.
            </td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
  var modal   = document.getElementById('modalSolicitud');
  var openBtn = document.getElementById('btnNuevaSolicitud');
  var closeBtn= document.getElementById('btnCerrarModal');

  openBtn.addEventListener('click',  function(){ modal.style.display = 'flex'; });
  closeBtn.addEventListener('click', function(){ modal.style.display = 'none'; });
  modal.addEventListener('click',    function(e){ if(e.target === modal) modal.style.display = 'none'; });
});
</script>
