<?php $pageTitle = 'Chat'; ?>
<div class="chat-wrapper">

  <!-- Lista conversaciones -->
  <div class="chat-list" id="chatList">
    <div class="chat-list-header">
      <div class="d-flex align-items-center justify-content-between">
        <span class="chat-list-title font-cinzel">Conversaciones ✦</span>
        <div class="d-flex align-items-center gap-2">
          <button id="nuevaConvBtn" title="Nueva conversación"
                  style="background:rgba(124,58,237,.2);border:1px solid rgba(124,58,237,.4);color:#c4b5fd;border-radius:8px;width:30px;height:30px;display:flex;align-items:center;justify-content:center;cursor:pointer;font-size:.85rem">
            <i class="fa fa-plus"></i>
          </button>
          <button class="d-lg-none btn btn-sm" id="closeChatList"
                  style="background:none;border:none;color:var(--pearl-muted);font-size:1.1rem">
            <i class="fa fa-xmark"></i>
          </button>
        </div>
      </div>
      <div class="mt-2">
        <input class="form-control form-control-sm" type="search"
               id="convSearch" placeholder="Buscar conversaciones...">
      </div>
    </div>

    <div class="overflow-auto flex-grow-1">
      <?php if(!empty($convs)): ?>
        <?php foreach($convs as $c): ?>
        <a href="<?= url('chat/'.$c['id']) ?>"
           class="chat-conv-item <?= (isset($convId) && $convId==$c['id']) ? 'active' : '' ?>">
          <img src="<?= avatar($c['otro_avatar'] ?? '') ?>" class="conv-avatar" alt="">
          <div class="flex-grow-1 overflow-hidden">
            <div class="d-flex align-items-center justify-content-between">
              <span class="conv-name"><?= e($c['otro_nombre'] ?? 'Usuario') ?></span>
              <span class="conv-time"><?= format_date($c['actualizado_en'] ?? '') ?></span>
            </div>
            <div class="conv-preview">
              <?php if(($c['no_leidos'] ?? 0) > 0): ?>
                <span class="nav-badge me-1"><?= $c['no_leidos'] ?></span>
              <?php endif; ?>
              <?= e(truncate($c['ultimo_mensaje'] ?? '...', 30)) ?>
            </div>
          </div>
        </a>
        <?php endforeach; ?>
      <?php else: ?>
        <div class="text-center py-4 px-3" style="color:var(--pearl-muted)">
          <i class="fa fa-comments fa-2x mb-2" style="opacity:.3;display:block"></i>
          <span style="font-size:.83rem">No tienes conversaciones</span>
        </div>
      <?php endif; ?>
    </div>
  </div>

  <!-- Área de mensajes -->
  <div class="chat-main">
    <?php if(isset($convId)): ?>  <!-- FIX: removed !empty($mensajes) so input shows on empty convs -->

      <!-- Header -->
      <div class="chat-header">
        <button class="btn d-lg-none me-2" id="openChatList"
                style="background:none;border:none;color:var(--pearl-dim);font-size:1rem;padding:.2rem .4rem">
          <i class="fa fa-arrow-left"></i>
        </button>
        <img src="<?= avatar($otroUsuario['avatar'] ?? '') ?>"
             class="rounded-circle" style="width:42px;height:42px;object-fit:cover;border:2px solid var(--purple-mid)" alt="">
        <div>
          <div style="font-weight:600;font-size:.9rem;color:var(--pearl)"><?= e($otroUsuario['nombre'] ?? '') ?></div>
          <div style="font-size:.72rem;color:var(--teal)">● En línea</div>
        </div>
        <a href="<?= url('artistas/'.$otroUsuario['id']) ?>"
           class="btn btn-outline-magic btn-sm ms-auto d-none d-md-inline-flex">
          <i class="fa fa-user me-1"></i>Ver perfil
        </a>
      </div>

      <!-- Mensajes -->
      <div class="chat-messages" id="chatMessages">
        <?php if(!empty($mensajes ?? [])): ?>
          <?php
          $prevDate = null;
          foreach($mensajes as $m):
            $msgDate = date('d/m/Y', strtotime($m['creado_en']));
            $isOwn   = (int)$m['remitente_id'] === Auth::id();
            if($msgDate !== $prevDate):
              $prevDate = $msgDate;
          ?>
            <div class="text-center my-2">
              <span style="background:rgba(124,58,237,.15);border:1px solid var(--border);border-radius:20px;padding:.2rem .9rem;font-size:.72rem;color:var(--pearl-muted)">
                <?= $msgDate === date('d/m/Y') ? 'Hoy' : $msgDate ?>
              </span>
            </div>
          <?php endif; ?>
            <div class="d-flex <?= $isOwn ? 'justify-content-end' : 'justify-content-start' ?> align-items-end gap-2">
              <?php if(!$isOwn): ?>
                <img src="<?= avatar($otroUsuario['avatar'] ?? '') ?>"
                     class="rounded-circle flex-shrink-0"
                     style="width:30px;height:30px;object-fit:cover;border:1px solid var(--border)" alt="">
              <?php endif; ?>
              <div class="msg-bubble <?= $isOwn ? 'sent' : 'received' ?>">
                <?= nl2br(e($m['mensaje'])) ?>
                <span class="msg-time"><?= date('H:i', strtotime($m['creado_en'])) ?></span>
              </div>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <!-- Conversación vacía -->
          <div class="d-flex flex-column align-items-center justify-content-center h-100 text-center p-4">
            <div style="font-size:2.5rem;margin-bottom:.75rem;opacity:.4">✦</div>
            <p style="font-size:.83rem;color:var(--pearl-muted)">
              Aún no hay mensajes. ¡Sé el primero en escribir!
            </p>
          </div>
        <?php endif; ?>
      </div>

      <!-- Input -->
      <form class="chat-input-area" id="chatForm"
            data-conv="<?= $convId ?>"
            data-url="<?= url('chat/enviar') ?>">
        <?= csrf_field() ?>
        <span style="color:var(--purple-mid);font-size:1rem">✦</span>
        <textarea class="chat-input" id="chatInput" rows="1"
                  placeholder="Escribe tu mensaje..." required></textarea>
        <button type="submit" class="chat-send-btn" title="Enviar">
          <i class="fa fa-paper-plane" style="font-size:.85rem"></i>
        </button>
      </form>

    <?php else: ?>
      <!-- Empty state — ninguna conversación seleccionada -->
      <div class="d-flex flex-column align-items-center justify-content-center h-100 text-center p-4">
        <div style="font-size:3rem;margin-bottom:1rem">💬</div>
        <h5 class="font-cinzel mb-2" style="color:var(--pearl-muted)">Tus mensajes</h5>
        <p style="font-size:.85rem;color:var(--pearl-muted);max-width:280px">
          Selecciona una conversación o inicia una nueva desde el perfil de un artista.
        </p>
        <button class="btn btn-outline-magic btn-sm mt-2 d-lg-none" id="openChatListEmpty">
          <i class="fa fa-comments me-1"></i>Ver conversaciones
        </button>
      </div>
    <?php endif; ?>
  </div>
</div>

<!-- Modal nueva conversación -->
<div id="nuevaConvModal" style="display:none;position:fixed;inset:0;z-index:9999;background:rgba(0,0,0,.6);backdrop-filter:blur(4px);align-items:center;justify-content:center">
  <div style="background:#16162a;border:1px solid rgba(124,58,237,.35);border-radius:18px;width:90%;max-width:420px;padding:1.5rem;box-shadow:0 20px 60px rgba(0,0,0,.8)">
    <div class="d-flex align-items-center justify-content-between mb-3">
      <h6 class="font-cinzel mb-0" style="color:var(--gold-light)">✦ Nueva conversación</h6>
      <button id="cerrarModalBtn" style="background:none;border:none;color:var(--pearl-muted);font-size:1.1rem;cursor:pointer"><i class="fa fa-xmark"></i></button>
    </div>
    <input type="text" id="buscarArtistaInput" class="form-control mb-3"
           placeholder="Buscar artista por nombre..."
           style="background:rgba(255,255,255,.05);border:1px solid rgba(124,58,237,.3);color:var(--pearl);border-radius:10px">
    <div id="resultadosArtistas" style="max-height:280px;overflow-y:auto">
      <p style="color:var(--pearl-muted);font-size:.83rem;text-align:center">Escribe al menos 2 letras para buscar</p>
    </div>
  </div>
</div>

<script src="<?= asset('js/chat.js') ?>"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
  // Auto-scroll
  var msgs = document.getElementById('chatMessages');
  if (msgs) msgs.scrollTop = msgs.scrollHeight;

  // Mobile toggle
  ['openChatList','openChatListEmpty'].forEach(function(id){
    var el = document.getElementById(id);
    if (el) el.addEventListener('click', function(){
      document.getElementById('chatList').classList.add('show');
    });
  });
  var closeBtn = document.getElementById('closeChatList');
  if (closeBtn) closeBtn.addEventListener('click', function(){
    document.getElementById('chatList').classList.remove('show');
  });

  // Search filter
  var convSearch = document.getElementById('convSearch');
  if (convSearch) convSearch.addEventListener('input', function(){
    var q = this.value.toLowerCase();
    document.querySelectorAll('.chat-conv-item').forEach(function(item){
      var name = (item.querySelector('.conv-name') || {textContent:''}).textContent.toLowerCase();
      item.style.display = name.includes(q) ? '' : 'none';
    });
  });

  // Modal nueva conversación
  var modal      = document.getElementById('nuevaConvModal');
  var openBtn    = document.getElementById('nuevaConvBtn');
  var closeModal = document.getElementById('cerrarModalBtn');
  var input      = document.getElementById('buscarArtistaInput');
  var results    = document.getElementById('resultadosArtistas');

  function showModal() {
    modal.style.display = 'flex';
    input.value = '';
    results.innerHTML = '<p style="color:var(--pearl-muted);font-size:.83rem;text-align:center">Escribe al menos 2 letras para buscar</p>';
    input.focus();
  }
  function hideModal() { modal.style.display = 'none'; }

  if (openBtn)    openBtn.addEventListener('click', showModal);
  if (closeModal) closeModal.addEventListener('click', hideModal);
  if (modal)      modal.addEventListener('click', function(e){ if (e.target === modal) hideModal(); });

  // Búsqueda de artistas
  var timer;
  if (input) input.addEventListener('input', function(){
    clearTimeout(timer);
    var q = this.value.trim();
    if (q.length < 2) {
      results.innerHTML = '<p style="color:var(--pearl-muted);font-size:.83rem;text-align:center">Escribe al menos 2 letras para buscar</p>';
      return;
    }
    results.innerHTML = '<p style="color:var(--pearl-muted);font-size:.83rem;text-align:center"><i class="fa fa-spinner fa-spin"></i> Buscando...</p>';
    timer = setTimeout(function(){
      fetch(BASE_URL + '/chat/buscar-artistas?q=' + encodeURIComponent(q), {
        headers: {'X-Requested-With': 'XMLHttpRequest'}
      })
      .then(function(r){ return r.json(); })
      .then(function(data){
        if (!data.length) {
          results.innerHTML = '<p style="color:var(--pearl-muted);font-size:.83rem;text-align:center">No se encontraron artistas</p>';
          return;
        }
        var html = '';
        data.forEach(function(a){
          html += '<a href="' + BASE_URL + '/chat/iniciar/' + a.id + '"'
                + ' style="display:flex;align-items:center;gap:.75rem;padding:.6rem .75rem;border-radius:10px;text-decoration:none;color:var(--pearl);transition:background .15s"'
                + ' onmouseover="this.style.background=\'rgba(124,58,237,.2)\'" onmouseout="this.style.background=\'none\'">'
                + '<img src="' + a.avatar_url + '" style="width:38px;height:38px;border-radius:50%;object-fit:cover;border:2px solid rgba(124,58,237,.4)" alt="">'
                + '<span style="font-size:.88rem;font-weight:500">' + a.nombre + '</span>'
                + '<i class="fa fa-paper-plane ms-auto" style="color:#a78bfa;font-size:.75rem"></i>'
                + '</a>';
        });
        results.innerHTML = html;
      })
      .catch(function(){
        results.innerHTML = '<p style="color:#f87171;font-size:.83rem;text-align:center">Error al buscar</p>';
      });
    }, 350);
  });
});
</script>
