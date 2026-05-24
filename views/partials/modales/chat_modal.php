<div class="modal fade" id="chatModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content" style="height:500px">
      <div class="modal-header py-2" style="background:linear-gradient(135deg,#2c1654,#6c3483)">
        <h5 class="modal-title text-white fw-bold"><i class="fa fa-comments me-2"></i>Chat</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body p-0 d-flex flex-column" style="overflow:hidden">
        <div id="chatModalMessages" class="flex-grow-1 p-3" style="overflow-y:auto;background:#f8f9fa;font-size:.9rem"></div>
        <div class="border-top p-2 d-flex gap-2">
          <input type="text" id="chatModalInput" class="form-control form-control-sm" placeholder="Escribe...">
          <button class="btn btn-primary btn-sm px-3" onclick="sendChatModal()"><i class="fa fa-paper-plane"></i></button>
        </div>
      </div>
    </div>
  </div>
</div>
