// ═══════════════ BERITA (NEWS) ═══════════════

const NEWS_KEY = 'kizuku_news_db';
let newsData = [];

function loadNews() {
    try { newsData = JSON.parse(localStorage.getItem(NEWS_KEY)) || []; } catch { newsData = []; }
    if (!newsData.length) {
        newsData = [
            { id: 1, judul: 'Pembukaan Batch Baru TG Maret 2025', kat: 'kat-info', emoji: '🎌', isi: 'Kuota 30 peserta, daftar sekarang!', tgl: '20/02/2025' },
            { id: 2, judul: '25 Alumni Berhasil Berangkat Bulan Ini', kat: 'kat-alumni', emoji: '🏆', isi: 'Selamat untuk para alumni!', tgl: '15/02/2025' },
            { id: 3, judul: 'Diskon 20% Kelas Bahasa Jepang', kat: 'kat-promo', emoji: '🎓', isi: 'Berlaku untuk pendaftar awal Maret.', tgl: '05/02/2025' },
            { id: 4, judul: '5 Tips Lolos Interview User Jepang', kat: 'kat-tips', emoji: '📚', isi: 'Simak tips dari trainer berpengalaman.', tgl: '10/02/2025' },
            { id: 5, judul: 'Kizuku Raih Akreditasi A Nasional', kat: 'kat-info', emoji: '✅', isi: 'Pengakuan resmi kualitas pelatihan kami.', tgl: '28/01/2025' },
            { id: 6, judul: 'MOU Baru dengan 15 Perusahaan Jepang', kat: 'kat-info', emoji: '🤝', isi: 'Membuka lebih banyak peluang untuk 2025.', tgl: '20/01/2025' },
        ];
        saveNews();
    }
}
function saveNews() { localStorage.setItem(NEWS_KEY, JSON.stringify(newsData)); }

function katLabel(k) { return { 'kat-info': 'Info Program', 'kat-alumni': 'Alumni', 'kat-promo': 'Promo', 'kat-tips': 'Tips' }[k] || k; }

function renderNewsAdmin() {
    const el = document.getElementById('news-admin-list'); if (!el) return;
    if (!newsData.length) { el.innerHTML = '<p style="color:var(--muted)">Belum ada berita.</p>'; return; }
    el.innerHTML = newsData.map(n => `
    <div class="news-item-admin">
      <div class="news-emo">${n.emoji}</div>
      <div style="flex:1;">
        <h5>${n.judul}</h5>
        <p>${n.isi}</p>
        <div class="news-item-actions">
          <span class="b-kategori ${n.kat}" style="font-size:10px;padding:2px 8px;">${katLabel(n.kat)}</span>
          <button class="tb-action danger" onclick="hapusBerita(${n.id})">🗑 Hapus</button>
          <span style="font-size:11px;color:var(--muted);padding:4px 6px;">${n.tgl}</span>
        </div>
      </div>
    </div>`).join('');
}

function tambahBerita() {
    const judul = document.getElementById('news-judul').value.trim();
    const msg = document.getElementById('news-msg');
    if (!judul) {
        msg.style.cssText = 'display:block;background:rgba(225,6,0,.10);color:var(--red)';
        msg.textContent = '⚠️ Judul berita wajib diisi.'; return;
    }
    newsData.unshift({
        id: Date.now(), judul,
        kat: document.getElementById('news-kat').value,
        emoji: document.getElementById('news-emoji').value.trim() || '📢',
        isi: document.getElementById('news-isi').value.trim(),
        tgl: new Date().toLocaleDateString('id-ID')
    });
    saveNews(); renderNewsAdmin();
    document.getElementById('news-judul').value = '';
    document.getElementById('news-isi').value = '';
    document.getElementById('news-emoji').value = '📢';
    msg.style.cssText = 'display:block;background:rgba(16,185,129,.12);color:#059669';
    msg.textContent = '✅ Berita berhasil dipublish!';
    setTimeout(() => msg.style.display = 'none', 3000);
}

function hapusBerita(id) {
    if (!confirm('Hapus berita ini?')) return;
    newsData = newsData.filter(n => n.id !== id); saveNews(); renderNewsAdmin();
}
