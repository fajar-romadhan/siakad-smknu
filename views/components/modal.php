<div class="modal-overlay" id="successModal" style="display:none">
    <div class="modal-box">
        <div class="modal-icon success">
            <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <polyline points="5 12 10 17 19 8"></polyline>
            </svg>
        </div>
        <p class="modal-text" id="modalText"></p>
        <button class="btn btn-primary" onclick="closeModal()">OK</button>
    </div>
</div>
<div class="modal-overlay" id="confirmModal" style="display:none">
    <div class="modal-box">
        <div class="modal-icon warning" style="width:56px;height:56px;border-radius:50%;background:#FEF3C7;color:#DC3545;display:flex;align-items:center;justify-content:center;">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
        </div>
        <p class="modal-text" id="confirmText">Apakah Anda yakin?</p>
        <div class="modal-actions">
            <button class="btn btn-secondary" onclick="closeConfirm()">Batal</button>
            <a href="#" class="btn btn-danger" id="confirmAction">Ya, Hapus</a>
        </div>
    </div>
</div>
