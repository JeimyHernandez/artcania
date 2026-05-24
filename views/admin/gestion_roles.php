<?php $pageTitle = 'Gestión de Roles'; ?>
<div class="d-flex justify-content-between align-items-center mb-4">
  <h2 class="fw-bold"><i class="fa fa-user-tag me-2"></i>Gestión de Roles</h2>
</div>
<div class="card shadow">
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-hover mb-0 tabla-dt">
        <thead class="table-dark"><tr>
          <th>#</th><th>Nombre</th><th>Email</th><th>Rol Actual</th><th>Cambiar Rol</th>
        </tr></thead>
        <tbody>
          <?php foreach($users as $u): ?>
          <tr>
            <td><?= e($u['id']) ?></td>
            <td><?= e($u['nombre']) ?></td>
            <td><?= e($u['email']) ?></td>
            <td><span class="badge bg-<?= $u['rol']==='admin'?'danger':($u['rol']==='artista'?'success':($u['rol']==='curador'?'warning':'secondary')) ?>"><?= e($u['rol']) ?></span></td>
            <td>
              <form class="d-inline-flex gap-1" onsubmit="cambiarRol(event,<?= $u['id'] ?>)">
                <select class="form-select form-select-sm" id="rol_<?= $u['id'] ?>" style="width:130px">
                  <?php foreach(['usuario','artista','curador','admin'] as $r): ?>
                  <option value="<?=$r?>" <?= $u['rol']===$r?'selected':'' ?>><?=ucfirst($r)?></option>
                  <?php endforeach; ?>
                </select>
                <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-save"></i></button>
              </form>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
<script>
const csrf = '<?= csrf_token() ?>';
function cambiarRol(e, id) {
  e.preventDefault();
  const rol = document.getElementById('rol_'+id).value;
  fetch('<?= url('admin/cambiar-rol') ?>', {
    method:'POST',
    headers:{'Content-Type':'application/x-www-form-urlencoded','X-Requested-With':'XMLHttpRequest'},
    body:`_csrf=${csrf}&id=${id}&rol=${rol}`
  }).then(r=>r.json()).then(d=>{
    if(d.ok){ const toast=document.createElement('div'); toast.className='alert alert-success position-fixed bottom-0 end-0 m-3'; toast.textContent='Rol actualizado'; document.body.appendChild(toast); setTimeout(()=>toast.remove(),2000); }
    else alert(d.error||'Error');
  });
}
</script>
