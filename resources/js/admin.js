'use strict';
// Admin-specific interactions
document.querySelectorAll('[data-rol-select]').forEach(btn => {
  btn.addEventListener('click', function () {
    const id = this.dataset.id;
    const rol = prompt('Nuevo rol (usuario, artista, curador, admin):');
    if (!rol) return;
    const csrf = document.querySelector('input[name="_csrf"]')?.value || '';
    fetch('/admin/cambiar-rol', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded', 'X-Requested-With': 'XMLHttpRequest' },
      body: `_csrf=${csrf}&id=${id}&rol=${rol}`
    }).then(r => r.json()).then(d => {
      if (d.ok) location.reload();
      else alert(d.error || 'Error');
    });
  });
});
