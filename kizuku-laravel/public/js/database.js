// ═══════════════ DATABASE SISWA ═══════════════

let siswas = [];
const STORAGE_KEY = 'kizuku_siswa_db';
const AVATAR_COLORS = [
    'linear-gradient(135deg,#E10600,#ff5e58)',
    'linear-gradient(135deg,#0F4C81,#1FA2C9)',
    'linear-gradient(135deg,#1a1a2e,#555)',
    'linear-gradient(135deg,#10b981,#059669)',
    'linear-gradient(135deg,#f59e0b,#d97706)',
];

function loadDB() {
    try { siswas = JSON.parse(localStorage.getItem(STORAGE_KEY)) || []; } catch { siswas = []; }
    if (siswas.length === 0) {
        siswas = [
            { id: 1, nama: 'Rizki Pratama', wa: '081234567890', email: 'rizki@email.com', kota: 'Surabaya', program: 'Tokutei Ginou (TG)', status: 'Berangkat', pendidikan: 'SMA/SMK', catatan: 'Lulus N4, berangkat Maret 2025', tglDaftar: '10/01/2025' },
            { id: 2, nama: 'Andi Setiawan', wa: '082345678901', email: 'andi@email.com', kota: 'Bandung', program: 'Engineering', status: 'Berangkat', pendidikan: 'D3', catatan: 'Penempatan Osaka', tglDaftar: '12/01/2025' },
            { id: 3, nama: 'Siti Nurhaliza', wa: '083456789012', email: 'siti@email.com', kota: 'Jakarta', program: 'Returnee / Ex Jepang', status: 'Aktif', pendidikan: 'S1', catatan: 'Target upgrade N3', tglDaftar: '15/01/2025' },
            { id: 4, nama: 'Budi Santoso', wa: '084567890123', email: 'budi@email.com', kota: 'Malang', program: 'Kelas Bahasa Jepang', status: 'Aktif', pendidikan: 'SMA/SMK', catatan: 'Level N5', tglDaftar: '18/01/2025' },
            { id: 5, nama: 'Dewi Rahayu', wa: '085678901234', email: 'dewi@email.com', kota: 'Yogyakarta', program: 'Tokutei Ginou (TG)', status: 'Proses', pendidikan: 'SMA/SMK', catatan: 'Menunggu jadwal interview', tglDaftar: '20/01/2025' },
            { id: 6, nama: 'Fajar Kurniawan', wa: '086789012345', email: 'fajar@email.com', kota: 'Medan', program: 'Engineering', status: 'Aktif', pendidikan: 'S1', catatan: 'Background teknik sipil', tglDaftar: '22/01/2025' },
            { id: 7, nama: 'Ayu Permatasari', wa: '087890123456', email: 'ayu@email.com', kota: 'Semarang', program: 'Kelas Bahasa Jepang', status: 'Lulus', pendidikan: 'D3', catatan: 'Lulus JLPT N4', tglDaftar: '25/01/2025' },
            { id: 8, nama: 'Hendra Wijaya', wa: '088901234567', email: 'hendra@email.com', kota: 'Solo', program: 'Tokutei Ginou (TG)', status: 'Proses', pendidikan: 'SMA/SMK', catatan: 'Dokumen sedang diproses', tglDaftar: '28/01/2025' },
        ];
        saveDB();
    }
}
function saveDB() { localStorage.setItem(STORAGE_KEY, JSON.stringify(siswas)); }

function updateStats() {
    document.getElementById('stat-total').textContent = siswas.length;
    document.getElementById('stat-aktif').textContent = siswas.filter(s => s.status === 'Aktif').length;
    document.getElementById('stat-berangkat').textContent = siswas.filter(s => s.status === 'Berangkat').length;
    document.getElementById('stat-proses').textContent = siswas.filter(s => s.status === 'Proses').length;
    document.getElementById('rpt-total').textContent = siswas.length;
    document.getElementById('rpt-berangkat').textContent = siswas.filter(s => s.status === 'Berangkat').length;
    renderLaporan();
}

function statusClass(s) {
    return { Aktif: 'aktif', Lulus: 'lulus', Proses: 'proses', Berangkat: 'berangkat' }[s] || 'aktif';
}

function renderTable(data) {
    const tbody = document.getElementById('siswa-tbody');
    const empty = document.getElementById('empty-msg');
    if (!data.length) { tbody.innerHTML = ''; empty.style.display = 'block'; return; }
    empty.style.display = 'none';
    tbody.innerHTML = data.map((s, i) => `
    <tr>
      <td style="color:var(--muted);font-size:12px;">${i + 1}</td>
      <td class="td-name"><span class="td-avatar" style="background:${AVATAR_COLORS[i % 5]}">${s.nama.charAt(0)}</span>${s.nama}</td>
      <td style="font-size:12.5px;max-width:140px;">${s.program}</td>
      <td style="font-size:12.5px;">${s.wa}</td>
      <td style="font-size:12.5px;">${s.kota}</td>
      <td style="font-size:12px;color:var(--muted);">${s.tglDaftar}</td>
      <td><span class="status-badge ${statusClass(s.status)}"><span class="status-dot"></span>${s.status}</span></td>
      <td>
        <button class="tb-action" onclick="editStatus(${s.id})">✏️ Status</button>
        <button class="tb-action danger" onclick="hapusSiswa(${s.id})">🗑</button>
      </td>
    </tr>`).join('');
}

function filterSiswa() {
    const q = document.getElementById('db-search').value.toLowerCase();
    const prog = document.getElementById('db-filter-prog').value;
    const stat = document.getElementById('db-filter-status').value;
    const filtered = siswas.filter(s =>
        (!q || s.nama.toLowerCase().includes(q) || s.kota.toLowerCase().includes(q)) &&
        (!prog || s.program === prog) &&
        (!stat || s.status === stat)
    );
    renderTable(filtered);
}

function tambahSiswa() {
    const nama = document.getElementById('add-nama').value.trim();
    const wa = document.getElementById('add-wa').value.trim();
    const kota = document.getElementById('add-kota').value.trim();
    const prog = document.getElementById('add-program').value;
    const msg = document.getElementById('add-msg');
    if (!nama || !wa || !kota || !prog) {
        msg.style.cssText = 'display:block;background:rgba(225,6,0,.10);color:var(--red)';
        msg.textContent = '⚠️ Mohon isi semua field wajib (nama, WA, kota, program).'; return;
    }
    siswas.push({
        id: Date.now(), nama, wa,
        email: document.getElementById('add-email').value.trim(),
        kota, program: prog,
        status: document.getElementById('add-status').value,
        pendidikan: document.getElementById('add-pendidikan').value,
        catatan: document.getElementById('add-catatan').value.trim(),
        tglDaftar: new Date().toLocaleDateString('id-ID')
    });
    saveDB(); renderTable(siswas); updateStats(); resetForm();
    msg.style.cssText = 'display:block;background:rgba(16,185,129,.12);color:#059669';
    msg.textContent = `✅ Data "${nama}" berhasil disimpan!`;
    setTimeout(() => msg.style.display = 'none', 3500);
}

function resetForm() {
    ['add-nama', 'add-wa', 'add-email', 'add-kota', 'add-catatan'].forEach(id => document.getElementById(id).value = '');
    document.getElementById('add-program').value = '';
    document.getElementById('add-status').value = 'Aktif';
    document.getElementById('add-pendidikan').value = 'SMA/SMK';
}

async function hapusSiswa(id) {
    const confirmed = await window.KizukuAlert.confirm({
        type: 'warning',
        title: 'Hapus Data Siswa',
        message: 'Yakin ingin menghapus data siswa ini?',
        confirmText: 'Ya, hapus',
    });
    if (!confirmed) return;
    siswas = siswas.filter(s => s.id !== id); saveDB(); filterSiswa(); updateStats();
}

async function editStatus(id) {
    const s = siswas.find(s => s.id === id); if (!s) return;
    const opts = ['Aktif', 'Proses', 'Berangkat', 'Lulus'];
    const newStat = await window.KizukuAlert.select({
        type: 'info',
        title: 'Ubah Status Siswa',
        message: `Pilih status baru untuk "${s.nama}".`,
        choices: opts,
        value: s.status,
        confirmText: 'Simpan',
    });
    if (newStat && opts.includes(newStat)) {
        s.status = newStat;
        saveDB();
        filterSiswa();
        updateStats();
        window.KizukuAlert.success('Status siswa berhasil diperbarui.');
    }
}

function exportCSV() {
    const header = ['No', 'Nama', 'Program', 'WA', 'Kota', 'Tgl Daftar', 'Status', 'Catatan'];
    const rows = siswas.map((s, i) => [i + 1, s.nama, s.program, s.wa, s.kota, s.tglDaftar, s.status, s.catatan]);
    const csv = [header, ...rows].map(r => r.map(v => `"${String(v).replace(/"/g, '""')}"`).join(',')).join('\n');
    const a = document.createElement('a');
    a.href = URL.createObjectURL(new Blob([csv], { type: 'text/csv;charset=utf-8;' }));
    a.download = `data-siswa-kizuku-${new Date().toISOString().slice(0, 10)}.csv`;
    a.click();
}

function renderLaporan() {
    const byProg = {}, byStatus = {};
    siswas.forEach(s => { byProg[s.program] = (byProg[s.program] || 0) + 1; byStatus[s.status] = (byStatus[s.status] || 0) + 1; });
    const total = siswas.length || 1;
    const bColor = { 'Tokutei Ginou (TG)': 'var(--red)', 'Engineering': 'var(--cyan)', 'Kelas Bahasa Jepang': '#555', 'Returnee / Ex Jepang': 'var(--blue)' };
    const sColor = { Aktif: '#10b981', Proses: '#f59e0b', Berangkat: 'var(--red)', Lulus: 'var(--blue)' };
    document.getElementById('rpt-program').innerHTML = Object.entries(byProg).map(([k, v]) => `
    <div style="display:flex;align-items:center;gap:12px;padding:10px 14px;background:var(--gray);border-radius:12px;">
      <div style="min-width:160px;font-size:13px;font-weight:700;">${k}</div>
      <div style="flex:1;background:rgba(17,17,17,.08);border-radius:99px;height:10px;overflow:hidden;">
        <div style="height:100%;width:${Math.round(v / total * 100)}%;background:${bColor[k] || 'var(--blue)'};border-radius:99px;"></div>
      </div>
      <div style="min-width:40px;text-align:right;font-size:13px;font-weight:800;">${v}</div>
    </div>`).join('') || '<p style="color:var(--muted)">Belum ada data.</p>';
    document.getElementById('rpt-status').innerHTML = Object.entries(byStatus).map(([k, v]) => `
    <div style="display:flex;align-items:center;gap:12px;padding:10px 14px;background:var(--gray);border-radius:12px;">
      <div style="min-width:120px;font-size:13px;font-weight:700;">${k}</div>
      <div style="flex:1;background:rgba(17,17,17,.08);border-radius:99px;height:10px;overflow:hidden;">
        <div style="height:100%;width:${Math.round(v / total * 100)}%;background:${sColor[k] || 'var(--blue)'};border-radius:99px;"></div>
      </div>
      <div style="min-width:40px;text-align:right;font-size:13px;font-weight:800;">${v}</div>
    </div>`).join('');
}
