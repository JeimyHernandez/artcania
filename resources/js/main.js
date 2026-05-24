/* ── Artcania Main JS ─────────────────────────────────────── */
'use strict';

// Auto-dismiss alerts
document.querySelectorAll('.alert').forEach(el => {
  setTimeout(() => { const bs = bootstrap.Alert.getOrCreateInstance(el); bs.close(); }, 5000);
});

// Image preview on file input
document.querySelectorAll('input[type="file"][accept*="image"]').forEach(input => {
  input.addEventListener('change', function () {
    const file = this.files[0];
    if (!file) return;
    const prev = this.nextElementSibling;
    if (prev && prev.tagName === 'IMG') {
      prev.src = URL.createObjectURL(file);
    }
  });
});

// AJAX helpers
function artFetch(url, opts = {}) {
  const csrf = document.querySelector('meta[name="csrf"]')?.content
    || document.querySelector('input[name="_csrf"]')?.value || '';
  opts.headers = Object.assign({ 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': csrf }, opts.headers || {});
  return fetch(url, opts).then(r => r.json());
}

// Favorite toggle
document.querySelectorAll('[data-toggle-fav]').forEach(btn => {
  btn.addEventListener('click', function () {
    const id = this.dataset.toggleFav;
    artFetch(BASE_URL + '/favorito/toggle', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: 'obra_id=' + id
    }).then(d => {
      this.classList.toggle('text-danger', d.added);
      this.classList.toggle('text-muted', !d.added);
    });
  });
});

// Confirm deletes
document.querySelectorAll('[data-confirm]').forEach(el => {
  el.addEventListener('click', e => {
    if (!confirm(el.dataset.confirm || '¿Estás seguro?')) e.preventDefault();
  });
});

// Smooth scroll to hash
if (window.location.hash) {
  const el = document.querySelector(window.location.hash);
  if (el) el.scrollIntoView({ behavior: 'smooth' });
}

// Notifications badge polling
(function pollNotifications() {
  const badge = document.getElementById('notifBadge');
  if (!badge) return;
  setInterval(() => {
    artFetch(BASE_URL + '/notificaciones/count').then(d => {
      badge.textContent = d.count || '';
      badge.style.display = d.count > 0 ? 'inline' : 'none';
    }).catch(() => {});
  }, 30000);
})();
