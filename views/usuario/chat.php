<?php $pageTitle = 'Chat'; ?>
<div class="chat-wrapper">

  <!-- Lista conversaciones -->
  <div class="chat-list" id="chatList">
    <div class="chat-list-header">
      <div class="d-flex align-items-center justify-content-between">
        <span class="chat-list-title font-cinzel">Conversaciones ✦</span>
        <button class="d-lg-none btn btn-sm" id="closeChatList"
                style="background:none;border:none;color:var(--pearl-muted);font-size:1.1rem">
          <i class="fa fa-xmark"></i>
        </button>
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
    <?php if(isset($convId) && !empty($mensajes ?? [])): ?>

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
      <!-- Empty state -->
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

<script src="<?= asset('js/chat.js') ?>"></script>
<script>
// Auto-scroll
var msgs = document.getElementById('chatMessages');
if(msgs) msgs.scrollTop = msgs.scrollHeight;

// Mobile toggle
$('#openChatList,#openChatListEmpty').on('click',function(){
  $('#chatList').addClass('show');
});
$('#closeChatList').on('click',function(){
  $('#chatList').removeClass('show');
});

// Search filter
$('#convSearch').on('input',function(){
  var q = $(this).val().toLowerCase();
  $('.chat-conv-item').each(function(){
    var name = $(this).find('.conv-name').text().toLowerCase();
    $(this).toggle(name.includes(q));
  });
});
</script>

