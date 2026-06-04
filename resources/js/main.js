/* Artcania main.js – jQuery + Bootstrap 5 */

$(function () {
  'use strict';

  // CSRF helper
  function csrfToken() {
    var m = document.cookie.match(/(?:^|; )csrf=([^;]+)/);
    return m ? m[1] : ($('input[name="_csrf_token"]').first().val() || '');
  }

  // Generic AJAX helper
  function artFetch(url, options) {
    return fetch(url, $.extend({
      headers: { 'X-Requested-With': 'XMLHttpRequest' }
    }, options));
  }

  // ── Favoritos ────────────────────────────────────────────────
  $(document).on('click', '.fav-btn', function (e) {
    e.stopPropagation();
    var $btn = $(this);
    var obraId = $btn.data('id');
    if (!obraId) return;

    artFetch(BASE_URL + '/favorito/toggle', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded',
        'X-Requested-With': 'XMLHttpRequest'
      },
      body: '_csrf=' + encodeURIComponent($('meta[name="csrf"]').attr('content') || '') + '&obra_id=' + obraId
    })
    .then(function(r){ return r.json(); })
    .then(function (data) {
      if (data.ok) {
        $btn.toggleClass('active', data.added);
        $btn.find('i')
          .toggleClass('fa-solid', data.added)
          .toggleClass('fa-regular', !data.added);
        // Actualizar contador
        var $count = $btn.find('#favCount, .fav-count');
        if ($count.length) {
          var n = parseInt($count.text()) || 0;
          $count.text(data.added ? n + 1 : Math.max(0, n - 1));
        }
      }
    })
    .catch(function(){});
  });

  // ── Contador de notificaciones ────────────────────────────────
  function checkNotif() {
    if (!$('#notifDot').length) return;
    artFetch(BASE_URL + '/notificaciones/count')
      .then(function(r){ return r.json(); })
      .then(function(d){
        if (d.count > 0) {
          $('#notifDot').removeClass('d-none');
        } else {
          $('#notifDot').addClass('d-none');
        }
      })
      .catch(function(){});
  }
  checkNotif();
  setInterval(checkNotif, 60000);

  // ── Toggle contraseña ─────────────────────────────────────────
  $(document).on('click', '.toggle-pass-btn', function(){
    var target = $(this).data('target');
    var $inp = $('#' + target);
    var $ico = $(this).find('i');
    if ($inp.attr('type') === 'password') {
      $inp.attr('type', 'text');
      $ico.removeClass('fa-eye').addClass('fa-eye-slash');
    } else {
      $inp.attr('type', 'password');
      $ico.removeClass('fa-eye-slash').addClass('fa-eye');
    }
  });

  // ── Auto-dismiss alerts ───────────────────────────────────────
  setTimeout(function () {
    $('.alert-success-magic, .alert-danger-magic').fadeOut(500);
  }, 6000);

  // ── Tooltips Bootstrap ────────────────────────────────────────
  $('[title]').not('[data-bs-toggle]').not('.dropdown-toggle').each(function(){
    if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
      new bootstrap.Tooltip(this, { trigger: 'hover' });
    }
  });

});  // ── Select2 global init ───────────────────────────────────────
  // Se ejecuta en DOMContentLoaded y también expone artSelect2()
  // para llamadas manuales después de renderizado dinámico.
  function artSelect2(ctx) {
    var $ctx = ctx ? $(ctx) : $(document);
    $ctx.find('select:not(.no-select2):not(.select2-hidden-accessible)').each(function () {
      var $sel  = $(this);
      var ph    = $sel.data('placeholder') || $sel.attr('placeholder') || 'Seleccionar…';
      var allow = $sel.prop('multiple') ? true : !!$sel.data('allow-clear');

      $sel.select2({
        theme        : 'artcania',
        placeholder  : ph,
        allowClear   : allow,
        width        : '100%',
        language     : {
          noResults : function () { return 'Sin resultados'; },
          searching  : function () { return 'Buscando…'; }
        }
      });
    });
  }

  // Init automático al cargar la página
  if (typeof $.fn.select2 === 'function') {
    artSelect2();
  } else {
    // Select2 no cargado aún: esperar al evento custom o al load
    $(window).on('select2:ready', function () { artSelect2(); });
  }

  // Exponer globalmente para vistas que generan selects dinámicos
  window.artSelect2 = artSelect2;
