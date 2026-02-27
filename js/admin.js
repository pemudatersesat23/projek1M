// ═══════════════ ADMIN UI ═══════════════

function openAdmin(tabId) {
    document.getElementById('admin-panel').classList.add('open');
    document.body.style.overflow = 'hidden';
    if (tabId) {
        document.querySelectorAll('.admin-tab').forEach(b => {
            b.classList.remove('active');
            if (b.getAttribute('onclick') && b.getAttribute('onclick').includes(tabId)) b.classList.add('active');
        });
        document.querySelectorAll('.admin-tab-content').forEach(t => t.style.display = 'none');
        const el = document.getElementById(tabId);
        if (el) el.style.display = 'block';
    }
    renderTable(siswas); updateStats(); renderNewsAdmin();
}

function closeAdmin() {
    document.getElementById('admin-panel').classList.remove('open');
    document.body.style.overflow = '';
}

function switchTab(tabId, btn) {
    document.querySelectorAll('.admin-tab-content').forEach(t => t.style.display = 'none');
    document.querySelectorAll('.admin-tab').forEach(b => b.classList.remove('active'));
    document.getElementById(tabId).style.display = 'block';
    btn.classList.add('active');
    if (tabId === 'tab-siswa') { renderTable(siswas); updateStats(); }
    if (tabId === 'tab-berita') renderNewsAdmin();
    if (tabId === 'tab-laporan') renderLaporan();
}

// Close admin panel when clicking outside modal
document.getElementById('admin-panel').addEventListener('click', function (e) { if (e.target === this) closeAdmin(); });

// Public form auto-save to DB
document.querySelector('.form-submit').addEventListener('click', function (e) {
    e.preventDefault();
    const inputs = document.querySelectorAll('#kontak .form-row input, #kontak .form-row select, #kontak .form-row textarea');
    const nama = inputs[0]?.value.trim();
    const wa = inputs[1]?.value.trim();
    const prog = document.querySelector('#kontak select')?.value;
    if (nama && wa) {
        siswas.push({
            id: Date.now(), nama, wa,
            email: inputs[2]?.value.trim() || '',
            kota: '-', program: prog || 'Belum dipilih',
            status: 'Proses', pendidikan: '-',
            catatan: document.querySelector('#kontak textarea')?.value.trim() || '',
            tglDaftar: new Date().toLocaleDateString('id-ID')
        });
        saveDB();
    }
    this.innerHTML = '✅ Terkirim! Kami akan menghubungi kamu segera.';
    this.style.background = 'linear-gradient(135deg,#10b981,#059669)';
    this.style.boxShadow = '0 8px 24px rgba(16,185,129,.30)';
    this.disabled = true;
});

function loadMoreNews() {
    const btn = document.getElementById('load-more-btn');
    btn.textContent = '✅ Semua berita telah ditampilkan';
    btn.disabled = true; btn.style.opacity = '.5';
}

// ═══════════════ INIT ═══════════════
loadDB(); loadNews();
renderTable(siswas); updateStats(); renderNewsAdmin();
