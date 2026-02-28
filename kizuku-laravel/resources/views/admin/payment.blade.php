@extends('layouts.admin')
@section('admin-title', 'Payment Settings')

@section('admin-content')
  <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
      <h3 class="text-lg font-bold text-slate-800">Pengaturan Pembayaran</h3>
      <p class="text-sm text-slate-500 mt-1">Kelola metode pembayaran dan instruksi untuk calon siswa.</p>
    </div>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    {{-- Payment Methods --}}
    <div>
      <div class="bg-white rounded-xl border border-slate-200 shadow-sm mb-6">
        <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between">
          <h4 class="font-bold text-slate-800 flex items-center gap-2">
            <span class="material-symbols-outlined text-primary">account_balance</span> Metode Pembayaran
          </h4>
        </div>
        <div class="p-6 space-y-4" id="paymentMethodsList">
          {{-- Default sample methods (stored in localStorage for now) --}}
        </div>
        <div class="px-6 pb-6">
          <button onclick="showAddPaymentForm()" class="w-full px-4 py-3 border-2 border-dashed border-slate-300 text-slate-500 rounded-xl text-sm font-medium hover:border-primary hover:text-primary transition-colors flex items-center justify-center gap-2">
            <span class="material-symbols-outlined">add</span> Tambah Metode Pembayaran
          </button>
        </div>
      </div>

      {{-- Add/Edit Payment Form --}}
      <div id="paymentForm" class="hidden bg-white rounded-xl border border-slate-200 shadow-sm mb-6">
        <div class="px-6 py-4 border-b border-slate-200">
          <h4 class="font-bold text-slate-800" id="paymentFormTitle">Tambah Metode Baru</h4>
        </div>
        <div class="p-6 space-y-4">
          <div>
            <label class="block text-xs font-semibold uppercase tracking-wide text-slate-500 mb-2">Nama Bank / E-Wallet</label>
            <input type="text" id="bankName" placeholder="Contoh: Bank BCA, Mandiri, Dana"
                   class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-primary outline-none">
          </div>
          <div>
            <label class="block text-xs font-semibold uppercase tracking-wide text-slate-500 mb-2">Nomor Rekening</label>
            <input type="text" id="accountNumber" placeholder="1234567890"
                   class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-primary outline-none">
          </div>
          <div>
            <label class="block text-xs font-semibold uppercase tracking-wide text-slate-500 mb-2">Atas Nama</label>
            <input type="text" id="accountName" placeholder="PT Kizuku Jaya"
                   class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-primary outline-none">
          </div>
          <div class="flex gap-3 pt-2">
            <button onclick="savePaymentMethod()" class="px-5 py-2.5 bg-primary text-white rounded-lg text-sm font-medium hover:bg-primary/90 transition-colors flex items-center gap-2">
              <span class="material-symbols-outlined text-lg">save</span> Simpan
            </button>
            <button onclick="hidePaymentForm()" class="px-5 py-2.5 border border-slate-200 text-slate-600 rounded-lg text-sm font-medium hover:bg-slate-50 transition-colors">
              Batal
            </button>
          </div>
        </div>
      </div>

      {{-- Payment Instructions --}}
      <div class="bg-white rounded-xl border border-slate-200 shadow-sm">
        <div class="px-6 py-4 border-b border-slate-200">
          <h4 class="font-bold text-slate-800 flex items-center gap-2">
            <span class="material-symbols-outlined text-primary">description</span> Instruksi Pembayaran
          </h4>
        </div>
        <div class="p-6">
          <textarea id="paymentInstructions" rows="4" placeholder="Contoh: Silakan transfer ke rekening di atas, lalu konfirmasi via WhatsApp dengan mengirimkan bukti transfer."
                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-primary outline-none resize-y"></textarea>
          <button onclick="saveInstructions()" class="mt-3 px-5 py-2.5 bg-primary text-white rounded-lg text-sm font-medium hover:bg-primary/90 transition-colors flex items-center gap-2">
            <span class="material-symbols-outlined text-lg">save</span> Simpan Instruksi
          </button>
        </div>
      </div>
    </div>

    {{-- Invoice Preview --}}
    <div>
      <div class="bg-white rounded-xl border border-slate-200 shadow-sm sticky top-24">
        <div class="px-6 py-4 border-b border-slate-200">
          <h4 class="font-bold text-slate-800 flex items-center gap-2">
            <span class="material-symbols-outlined text-primary">preview</span> Preview Invoice Siswa
          </h4>
          <p class="text-xs text-slate-400 mt-1">Tampilan yang dilihat calon siswa setelah mendaftar</p>
        </div>
        <div class="p-6">
          <div class="bg-gradient-to-br from-slate-50 to-slate-100 rounded-xl p-6 border border-slate-200">
            <div class="text-center mb-6">
              <div class="w-14 h-14 bg-primary rounded-xl flex items-center justify-center mx-auto mb-3">
                <span class="text-white font-bold text-lg">K</span>
              </div>
              <h5 class="font-bold text-slate-800">LPK Kizuku</h5>
              <p class="text-xs text-slate-500">Invoice Pendaftaran</p>
            </div>

            <div id="invoicePreviewMethods" class="space-y-3 mb-6">
              {{-- Populated by JS --}}
            </div>

            <div class="border-t border-slate-200 pt-4 mb-4">
              <p id="invoicePreviewInstructions" class="text-xs text-slate-600 leading-relaxed"></p>
            </div>

            <button class="w-full px-4 py-3 bg-emerald-600 text-white rounded-xl text-sm font-medium hover:bg-emerald-700 transition-colors flex items-center justify-center gap-2">
              <span class="material-symbols-outlined text-lg">chat</span> Konfirmasi via WhatsApp
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('admin-scripts')
<script>
  // Payment methods stored in localStorage
  let paymentMethods = JSON.parse(localStorage.getItem('kizuku_payment_methods') || '[]');
  let paymentInstructions = localStorage.getItem('kizuku_payment_instructions') || '';
  let editingIndex = -1;

  // Default data if empty
  if (paymentMethods.length === 0) {
    paymentMethods = [
      { bank: 'Bank BCA', number: '1234567890', name: 'PT Kizuku Jaya' },
      { bank: 'Bank Mandiri', number: '0987654321', name: 'PT Kizuku Jaya' },
    ];
    localStorage.setItem('kizuku_payment_methods', JSON.stringify(paymentMethods));
  }
  if (!paymentInstructions) {
    paymentInstructions = 'Silakan transfer ke salah satu rekening di atas sesuai jumlah yang ditentukan. Setelah transfer, konfirmasi pembayaran via WhatsApp dengan mengirimkan bukti transfer.';
    localStorage.setItem('kizuku_payment_instructions', paymentInstructions);
  }

  function renderMethods() {
    const container = document.getElementById('paymentMethodsList');
    const preview = document.getElementById('invoicePreviewMethods');
    if (paymentMethods.length === 0) {
      container.innerHTML = '<p class="text-sm text-slate-400 text-center py-4">Belum ada metode pembayaran.</p>';
      preview.innerHTML = '<p class="text-xs text-slate-400 text-center">Belum ada metode.</p>';
      return;
    }
    container.innerHTML = paymentMethods.map((m, i) => `
      <div class="flex items-center gap-4 p-4 bg-slate-50 rounded-xl border border-slate-200">
        <div class="w-10 h-10 bg-primary/10 rounded-lg flex items-center justify-center flex-shrink-0">
          <span class="material-symbols-outlined text-primary">account_balance</span>
        </div>
        <div class="flex-1 min-w-0">
          <p class="font-semibold text-sm text-slate-800">${m.bank}</p>
          <p class="text-xs text-slate-500">${m.number} · a.n ${m.name}</p>
        </div>
        <div class="flex gap-1">
          <button onclick="editMethod(${i})" class="p-1.5 rounded-lg hover:bg-slate-200 text-slate-400 hover:text-primary transition-colors">
            <span class="material-symbols-outlined text-lg">edit</span>
          </button>
          <button onclick="deleteMethod(${i})" class="p-1.5 rounded-lg hover:bg-red-50 text-slate-400 hover:text-accent-red transition-colors">
            <span class="material-symbols-outlined text-lg">delete</span>
          </button>
        </div>
      </div>
    `).join('');

    preview.innerHTML = paymentMethods.map(m => `
      <div class="p-3 bg-white rounded-lg border border-slate-200">
        <p class="font-semibold text-xs text-slate-800">${m.bank}</p>
        <p class="text-xs text-slate-500 mt-0.5">No. ${m.number}</p>
        <p class="text-xs text-slate-500">a.n ${m.name}</p>
      </div>
    `).join('');
  }

  function showAddPaymentForm() {
    editingIndex = -1;
    document.getElementById('bankName').value = '';
    document.getElementById('accountNumber').value = '';
    document.getElementById('accountName').value = '';
    document.getElementById('paymentFormTitle').textContent = 'Tambah Metode Baru';
    document.getElementById('paymentForm').classList.remove('hidden');
  }

  function hidePaymentForm() {
    document.getElementById('paymentForm').classList.add('hidden');
  }

  function editMethod(index) {
    editingIndex = index;
    const m = paymentMethods[index];
    document.getElementById('bankName').value = m.bank;
    document.getElementById('accountNumber').value = m.number;
    document.getElementById('accountName').value = m.name;
    document.getElementById('paymentFormTitle').textContent = 'Edit Metode';
    document.getElementById('paymentForm').classList.remove('hidden');
  }

  function savePaymentMethod() {
    const bank = document.getElementById('bankName').value.trim();
    const number = document.getElementById('accountNumber').value.trim();
    const name = document.getElementById('accountName').value.trim();
    if (!bank || !number || !name) { alert('Semua field harus diisi!'); return; }

    if (editingIndex >= 0) {
      paymentMethods[editingIndex] = { bank, number, name };
    } else {
      paymentMethods.push({ bank, number, name });
    }
    localStorage.setItem('kizuku_payment_methods', JSON.stringify(paymentMethods));
    renderMethods();
    hidePaymentForm();
  }

  function deleteMethod(index) {
    if (!confirm('Hapus metode pembayaran ini?')) return;
    paymentMethods.splice(index, 1);
    localStorage.setItem('kizuku_payment_methods', JSON.stringify(paymentMethods));
    renderMethods();
  }

  function saveInstructions() {
    paymentInstructions = document.getElementById('paymentInstructions').value;
    localStorage.setItem('kizuku_payment_instructions', paymentInstructions);
    document.getElementById('invoicePreviewInstructions').textContent = paymentInstructions;
    alert('Instruksi berhasil disimpan!');
  }

  // Init
  document.addEventListener('DOMContentLoaded', function() {
    renderMethods();
    document.getElementById('paymentInstructions').value = paymentInstructions;
    document.getElementById('invoicePreviewInstructions').textContent = paymentInstructions;
  });
</script>
@endsection
