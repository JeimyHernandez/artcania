'use strict';

document.addEventListener('DOMContentLoaded', function () {
  var form = document.getElementById('chatForm');
  if (!form) return;

  var input    = document.getElementById('chatInput');
  var messages = document.getElementById('chatMessages');
  var convId   = form.dataset.conv;
  var url      = form.dataset.url;

  // Auto-resize textarea
  input.addEventListener('input', function () {
    this.style.height = 'auto';
    this.style.height = Math.min(this.scrollHeight, 120) + 'px';
  });

  // Enviar con Enter (Shift+Enter = salto de línea)
  input.addEventListener('keydown', function (e) {
    if (e.key === 'Enter' && !e.shiftKey) {
      e.preventDefault();
      form.dispatchEvent(new Event('submit'));
    }
  });

  form.addEventListener('submit', function (e) {
    e.preventDefault();

    var mensaje = input.value.trim();
    if (!mensaje) return;

    var csrfField = form.querySelector('[name="csrf_token"]');
    var csrfToken = csrfField ? csrfField.value : '';

    // Deshabilitar mientras se envía
    input.disabled = true;
    form.querySelector('.chat-send-btn').disabled = true;

    fetch(url, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded',
        'X-Requested-With': 'XMLHttpRequest'
      },
      body: new URLSearchParams({
        conv_id:    convId,
        mensaje:    mensaje,
        csrf_token: csrfToken
      })
    })
    .then(function (r) { return r.json(); })
    .then(function (data) {
      if (data.ok) {
        // Quitar placeholder "sin mensajes" si existe
        var empty = messages.querySelector('.chat-empty-placeholder');
        if (empty) empty.remove();

        // Agregar burbuja propia
        var bubble = document.createElement('div');
        bubble.className = 'd-flex justify-content-end align-items-end gap-2';
        bubble.innerHTML =
          '<div class="msg-bubble sent">' +
            mensaje.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/\n/g,'<br>') +
            '<span class="msg-time">' + data.ts + '</span>' +
          '</div>';
        messages.appendChild(bubble);
        messages.scrollTop = messages.scrollHeight;

        // Limpiar input
        input.value = '';
        input.style.height = 'auto';
      } else {
        alert(data.error || 'Error al enviar el mensaje');
      }
    })
    .catch(function () {
      alert('Error de conexión al enviar el mensaje');
    })
    .finally(function () {
      input.disabled = false;
      form.querySelector('.chat-send-btn').disabled = false;
      input.focus();
    });
  });
});
