@once
@php
    $flashMessages = [];

    foreach (['success', 'error', 'warning', 'info'] as $flashType) {
        if (session()->has($flashType)) {
            $flashMessages[] = [
                'type' => $flashType,
                'message' => session($flashType),
            ];
        }
    }

    if ($errors->any()) {
        $flashMessages[] = [
            'type' => 'error',
            'message' => $errors->first(),
        ];
    }
@endphp

<style>
    .kz-alert-toast-stack {
        position: fixed;
        right: 24px;
        bottom: 24px;
        z-index: 99999;
        display: flex;
        flex-direction: column;
        gap: 12px;
        width: min(390px, calc(100vw - 32px));
        pointer-events: none;
    }

    .kz-alert-toast {
        pointer-events: auto;
        display: grid;
        grid-template-columns: 44px 1fr auto;
        gap: 12px;
        align-items: center;
        padding: 14px 14px 14px 12px;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-left: 5px solid var(--kz-alert-color, #0067a3);
        border-radius: 16px;
        box-shadow: 0 20px 55px rgba(15, 28, 35, 0.16);
        opacity: 0;
        transform: translateY(16px) scale(0.98);
        transition: opacity .22s ease, transform .22s ease;
    }

    .kz-alert-toast.show {
        opacity: 1;
        transform: translateY(0) scale(1);
    }

    .kz-alert-icon {
        width: 42px;
        height: 42px;
        display: grid;
        place-items: center;
        border-radius: 14px;
        background: color-mix(in srgb, var(--kz-alert-color, #0067a3) 12%, white);
        color: var(--kz-alert-color, #0067a3);
        flex-shrink: 0;
    }

    .kz-alert-title {
        font-size: 14px;
        line-height: 1.3;
        font-weight: 800;
        color: #0f1c23;
        margin: 0 0 2px;
    }

    .kz-alert-message {
        font-size: 13px;
        line-height: 1.55;
        color: #64748b;
        margin: 0;
    }

    .kz-alert-close {
        width: 30px;
        height: 30px;
        border: 0;
        border-radius: 10px;
        background: #f8fafc;
        color: #64748b;
        cursor: pointer;
        display: grid;
        place-items: center;
        transition: background .18s ease, color .18s ease;
    }

    .kz-alert-close:hover {
        background: #f1f5f9;
        color: #0f1c23;
    }

    .kz-alert-modal-backdrop {
        position: fixed;
        inset: 0;
        z-index: 100000;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 24px;
        background: rgba(15, 28, 35, 0.58);
        backdrop-filter: blur(8px);
    }

    .kz-alert-modal-backdrop.show {
        display: flex;
    }

    .kz-alert-modal {
        width: min(430px, 100%);
        background: #ffffff;
        border: 1px solid rgba(226, 232, 240, .9);
        border-radius: 22px;
        box-shadow: 0 30px 80px rgba(15, 28, 35, .26);
        overflow: hidden;
        opacity: 0;
        transform: translateY(18px) scale(.98);
        transition: opacity .2s ease, transform .2s ease;
    }

    .kz-alert-modal-backdrop.show .kz-alert-modal {
        opacity: 1;
        transform: translateY(0) scale(1);
    }

    .kz-alert-modal-head {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 22px 24px 12px;
    }

    .kz-alert-modal-icon {
        width: 48px;
        height: 48px;
        display: grid;
        place-items: center;
        border-radius: 16px;
        background: color-mix(in srgb, var(--kz-alert-color, #0067a3) 12%, white);
        color: var(--kz-alert-color, #0067a3);
        flex-shrink: 0;
    }

    .kz-alert-modal-title {
        font-size: 18px;
        line-height: 1.25;
        font-weight: 850;
        color: #0f1c23;
        margin: 0;
    }

    .kz-alert-modal-body {
        padding: 0 24px 22px 86px;
        font-size: 14px;
        line-height: 1.65;
        color: #64748b;
    }

    .kz-alert-select {
        display: none;
        width: calc(100% - 110px);
        margin: -8px 24px 24px 86px;
        padding: 11px 12px;
        border: 1px solid #cbd5e1;
        border-radius: 12px;
        background: #ffffff;
        color: #0f172a;
        font-size: 14px;
        outline: none;
    }

    .kz-alert-select:focus {
        border-color: var(--kz-alert-color, #0067a3);
        box-shadow: 0 0 0 3px color-mix(in srgb, var(--kz-alert-color, #0067a3) 16%, transparent);
    }

    .kz-alert-modal-actions {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        padding: 16px 24px 22px;
        background: #f8fafc;
    }

    .kz-alert-btn {
        border: 0;
        border-radius: 12px;
        padding: 10px 16px;
        font-size: 13px;
        font-weight: 800;
        cursor: pointer;
        transition: transform .16s ease, box-shadow .16s ease, background .16s ease;
    }

    .kz-alert-btn:active {
        transform: scale(.98);
    }

    .kz-alert-btn-secondary {
        background: #e2e8f0;
        color: #334155;
    }

    .kz-alert-btn-secondary:hover {
        background: #cbd5e1;
    }

    .kz-alert-btn-primary {
        background: var(--kz-alert-color, #0067a3);
        color: #ffffff;
        box-shadow: 0 12px 24px color-mix(in srgb, var(--kz-alert-color, #0067a3) 24%, transparent);
    }

    .kz-alert-btn-primary:hover {
        filter: brightness(.96);
    }

    @media (max-width: 640px) {
        .kz-alert-toast-stack {
            right: 16px;
            bottom: 16px;
        }

        .kz-alert-modal-body {
            padding-left: 24px;
        }

        .kz-alert-select {
            width: calc(100% - 48px);
            margin: -4px 24px 20px;
        }

        .kz-alert-modal-actions {
            flex-direction: column-reverse;
        }

        .kz-alert-btn {
            width: 100%;
        }
    }
</style>

<div class="kz-alert-toast-stack" id="kzAlertToastStack" aria-live="polite" aria-atomic="true"></div>

<div class="kz-alert-modal-backdrop" id="kzAlertModal" role="dialog" aria-modal="true" aria-labelledby="kzAlertModalTitle">
    <div class="kz-alert-modal">
        <div class="kz-alert-modal-head">
            <div class="kz-alert-modal-icon">
                <span class="material-symbols-outlined" id="kzAlertModalIcon">info</span>
            </div>
            <h3 class="kz-alert-modal-title" id="kzAlertModalTitle">Informasi</h3>
        </div>
        <div class="kz-alert-modal-body" id="kzAlertModalMessage"></div>
        <select class="kz-alert-select" id="kzAlertSelect"></select>
        <div class="kz-alert-modal-actions">
            <button type="button" class="kz-alert-btn kz-alert-btn-secondary" id="kzAlertCancelBtn">Batal</button>
            <button type="button" class="kz-alert-btn kz-alert-btn-primary" id="kzAlertConfirmBtn">OK</button>
        </div>
    </div>
</div>

<script>
    (() => {
        const flashMessages = @json($flashMessages);
        const typeMeta = {
            success: { title: 'Berhasil', icon: 'check_circle', color: '#059669' },
            error: { title: 'Terjadi Kesalahan', icon: 'error', color: '#E31E24' },
            warning: { title: 'Perhatian', icon: 'warning', color: '#d97706' },
            info: { title: 'Informasi', icon: 'info', color: '#0067a3' },
            confirm: { title: 'Konfirmasi Aksi', icon: 'help', color: '#0067a3' },
        };

        const toastStack = document.getElementById('kzAlertToastStack');
        const modal = document.getElementById('kzAlertModal');
        const modalIcon = document.getElementById('kzAlertModalIcon');
        const modalTitle = document.getElementById('kzAlertModalTitle');
        const modalMessage = document.getElementById('kzAlertModalMessage');
        const modalSelect = document.getElementById('kzAlertSelect');
        const cancelBtn = document.getElementById('kzAlertCancelBtn');
        const confirmBtn = document.getElementById('kzAlertConfirmBtn');
        let activeResolver = null;

        function meta(type) {
            return typeMeta[type] || typeMeta.info;
        }

        function closeToast(toast) {
            toast.classList.remove('show');
            setTimeout(() => toast.remove(), 220);
        }

        function notify(options = {}) {
            const type = options.type || 'info';
            const current = meta(type);
            const toast = document.createElement('div');
            toast.className = 'kz-alert-toast';
            toast.style.setProperty('--kz-alert-color', current.color);
            toast.innerHTML = `
                <div class="kz-alert-icon"><span class="material-symbols-outlined">${current.icon}</span></div>
                <div>
                    <p class="kz-alert-title">${escapeHtml(options.title || current.title)}</p>
                    <p class="kz-alert-message">${escapeHtml(options.message || '')}</p>
                </div>
                <button type="button" class="kz-alert-close" aria-label="Tutup alert">
                    <span class="material-symbols-outlined" style="font-size:18px;">close</span>
                </button>
            `;

            toast.querySelector('.kz-alert-close').addEventListener('click', () => closeToast(toast));
            toastStack.appendChild(toast);
            requestAnimationFrame(() => toast.classList.add('show'));

            const duration = Number(options.duration ?? 4200);
            if (duration > 0) {
                setTimeout(() => closeToast(toast), duration);
            }

            return toast;
        }

        function openModal(options = {}) {
            const type = options.type || 'info';
            const current = meta(type);
            modal.style.setProperty('--kz-alert-color', current.color);
            modalIcon.textContent = options.icon || current.icon;
            modalTitle.textContent = options.title || current.title;
            modalMessage.textContent = options.message || '';
            confirmBtn.textContent = options.confirmText || 'OK';
            cancelBtn.textContent = options.cancelText || 'Batal';
            cancelBtn.style.display = options.showCancel === false ? 'none' : '';
            modal.dataset.mode = Array.isArray(options.choices) ? 'select' : '';
            modalSelect.innerHTML = '';
            modalSelect.style.display = 'none';

            if (Array.isArray(options.choices)) {
                options.choices.forEach(choice => {
                    const value = typeof choice === 'object' ? choice.value : choice;
                    const label = typeof choice === 'object' ? choice.label : choice;
                    const option = document.createElement('option');
                    option.value = value;
                    option.textContent = label;
                    modalSelect.appendChild(option);
                });
                modalSelect.value = options.value || modalSelect.value;
                modalSelect.style.display = 'block';
            }

            modal.classList.add('show');
            (modal.dataset.mode === 'select' ? modalSelect : confirmBtn).focus();

            return new Promise(resolve => {
                activeResolver = resolve;
            });
        }

        function resolveModal(value) {
            if (!activeResolver) return;
            const resolve = activeResolver;
            activeResolver = null;
            modal.classList.remove('show');
            resolve(value);
        }

        function escapeHtml(value) {
            return String(value ?? '')
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }

        cancelBtn.addEventListener('click', () => resolveModal(false));
        confirmBtn.addEventListener('click', () => {
            resolveModal(modal.dataset.mode === 'select' ? modalSelect.value : true);
        });
        modal.addEventListener('click', event => {
            if (event.target === modal && cancelBtn.style.display !== 'none') {
                resolveModal(false);
            }
        });
        document.addEventListener('keydown', event => {
            if (event.key === 'Escape' && modal.classList.contains('show') && cancelBtn.style.display !== 'none') {
                resolveModal(false);
            }
        });

        window.KizukuAlert = {
            notify,
            success: message => notify({ type: 'success', message }),
            error: message => notify({ type: 'error', message }),
            warning: message => notify({ type: 'warning', message }),
            info: message => notify({ type: 'info', message }),
            alert: (message, options = {}) => openModal({
                type: options.type || 'info',
                title: options.title || meta(options.type || 'info').title,
                message,
                confirmText: options.confirmText || 'Mengerti',
                showCancel: false,
            }),
            confirm: (options = {}) => openModal({
                type: options.type || 'confirm',
                title: options.title || 'Konfirmasi Aksi',
                message: options.message || 'Lanjutkan aksi ini?',
                confirmText: options.confirmText || 'Ya, lanjutkan',
                cancelText: options.cancelText || 'Batal',
            }),
            select: (options = {}) => openModal({
                type: options.type || 'info',
                title: options.title || 'Pilih Opsi',
                message: options.message || '',
                confirmText: options.confirmText || 'Simpan',
                cancelText: options.cancelText || 'Batal',
                choices: options.choices || [],
                value: options.value,
            }),
        };

        window.alert = message => {
            window.KizukuAlert.alert(message);
        };

        document.addEventListener('submit', async event => {
            const form = event.target.closest('form[data-confirm]');
            if (!form || form.dataset.kzConfirmed === '1') return;

            event.preventDefault();
            const confirmed = await window.KizukuAlert.confirm({
                type: form.dataset.confirmType || 'warning',
                title: form.dataset.confirmTitle || 'Konfirmasi Aksi',
                message: form.dataset.confirm || 'Lanjutkan aksi ini?',
                confirmText: form.dataset.confirmText || 'Ya, lanjutkan',
                cancelText: form.dataset.cancelText || 'Batal',
            });

            if (confirmed) {
                form.dataset.kzConfirmed = '1';
                form.requestSubmit();
            }
        }, true);

        document.addEventListener('click', async event => {
            const trigger = event.target.closest('[data-confirm-submit]');
            if (!trigger) return;

            event.preventDefault();
            const target = document.querySelector(trigger.dataset.confirmSubmit);
            if (!target) return;

            const confirmed = await window.KizukuAlert.confirm({
                type: trigger.dataset.confirmType || 'warning',
                title: trigger.dataset.confirmTitle || 'Konfirmasi Aksi',
                message: trigger.dataset.confirm || 'Lanjutkan aksi ini?',
                confirmText: trigger.dataset.confirmText || 'Ya, lanjutkan',
                cancelText: trigger.dataset.cancelText || 'Batal',
            });

            if (confirmed) {
                target.requestSubmit();
            }
        });

        document.addEventListener('DOMContentLoaded', () => {
            flashMessages.forEach(item => {
                notify({ type: item.type, message: item.message });
            });
        });
    })();
</script>
@endonce
