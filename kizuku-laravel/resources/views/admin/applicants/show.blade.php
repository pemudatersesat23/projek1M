@extends('layouts.admin')
@section('admin-title', 'Detail Pendaftar: ' . $applicant->nama)

@section('admin-content')
  <div class="mb-8">
    <a href="{{ route('admin.applicants.index') }}" class="text-sm text-slate-500 hover:text-primary flex items-center gap-1 mb-2">
      <span class="material-symbols-outlined text-sm">arrow_back</span> Kembali ke Daftar
    </a>
    <h3 class="text-slate-800 font-bold text-2xl">Detail Pendaftar</h3>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <div class="lg:col-span-2 space-y-8">
      {{-- Informasi Personal --}}
      <div class="bg-white p-8 rounded-2xl border border-slate-200 shadow-sm">
        <h4 class="font-bold text-slate-800 mb-6 flex items-center gap-2">
          <span class="material-symbols-outlined text-primary">person</span> Informasi Personal
        </h4>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Nama Lengkap</p>
            <p class="text-slate-800 font-bold">{{ $applicant->nama }}</p>
          </div>
          <div>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Jenis Kelamin</p>
            <p class="text-slate-800 font-bold">{{ $applicant->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</p>
          </div>
          <div>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Tempat, Tgl Lahir</p>
            <p class="text-slate-800 font-bold">{{ $applicant->tempat_lahir }}, {{ $applicant->tanggal_lahir->format('d/m/Y') }}</p>
          </div>
          <div>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Pendidikan Terakhir</p>
            <p class="text-slate-800 font-bold">{{ $applicant->pendidikan }}</p>
          </div>
          <div class="md:col-span-2">
            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Alamat Lengkap</p>
            <p class="text-slate-800 font-medium leading-relaxed">{{ $applicant->alamat }}</p>
          </div>
        </div>
      </div>

      {{-- Kontak & Pengalaman --}}
      <div class="bg-white p-8 rounded-2xl border border-slate-200 shadow-sm">
        <h4 class="font-bold text-slate-800 mb-6 flex items-center gap-2">
          <span class="material-symbols-outlined text-primary">contact_mail</span> Kontak & Pengalaman
        </h4>
        <div class="space-y-6">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="flex items-center gap-3">
              <div class="p-2 bg-slate-50 rounded-lg">
                <span class="material-symbols-outlined text-slate-400">phone</span>
              </div>
              <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase">No. HP / WA</p>
                <p class="text-slate-800 font-bold">{{ $applicant->phone }}</p>
              </div>
            </div>
            <div class="flex items-center gap-3">
              <div class="p-2 bg-slate-50 rounded-lg">
                <span class="material-symbols-outlined text-slate-400">mail</span>
              </div>
              <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase">Email</p>
                <p class="text-slate-800 font-bold">{{ $applicant->email }}</p>
              </div>
            </div>
          </div>
          <div class="p-4 bg-slate-50 rounded-xl">
            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Pengalaman Kerja</p>
            <p class="text-slate-700 text-sm whitespace-pre-line">{{ $applicant->pengalaman_kerja ?: ($applicant->pengalaman ?: 'Tidak ada pengalaman kerja dicantumkan.') }}</p>
          </div>
          <div class="p-4 bg-slate-50 rounded-xl">
            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Motivasi</p>
            <p class="text-slate-700 text-sm whitespace-pre-line">{{ $applicant->motivasi ?: 'Tidak ada motivasi dicantumkan.' }}</p>
          </div>
        </div>
      </div>

      {{-- Informasi Khusus Recruitment --}}
      @if($applicant->tinggi_badan || $applicant->bidang_ssw || $applicant->ipk || $applicant->shift_kursus)
      <div class="bg-white p-8 rounded-2xl border border-slate-200 shadow-sm">
        <h4 class="font-bold text-slate-800 mb-6 flex items-center gap-2">
          <span class="material-symbols-outlined text-primary">assignment_ind</span> Informasi Khusus Recruitment
        </h4>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          @if($applicant->tinggi_badan)
          <div>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Tinggi / Berat Badan</p>
            <p class="text-slate-800 font-bold">{{ $applicant->tinggi_badan }} cm / {{ $applicant->berat_badan }} kg</p>
          </div>
          @endif
          @if($applicant->kondisi_mata)
          <div>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Kondisi Mata</p>
            <p class="text-slate-800 font-bold">{{ $applicant->kondisi_mata }}</p>
          </div>
          @endif
          @if($applicant->tinggi_badan)
          <div class="flex gap-4">
            <div>
              <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Tato</p>
              <span class="px-2 py-1 {{ $applicant->tato ? 'bg-red-100 text-red-600' : 'bg-green-100 text-green-600' }} rounded text-[10px] font-bold uppercase">
                {{ $applicant->tato ? 'Ada' : 'Tidak Ada' }}
              </span>
            </div>
            <div>
              <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Merokok</p>
              <span class="px-2 py-1 {{ $applicant->merokok ? 'bg-red-100 text-red-600' : 'bg-green-100 text-green-600' }} rounded text-[10px] font-bold uppercase">
                {{ $applicant->merokok ? 'Ya' : 'Tidak' }}
              </span>
            </div>
          </div>
          @endif
          @if($applicant->bidang_ssw)
          <div>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Bidang SSW</p>
            <p class="text-slate-800 font-bold">{{ $applicant->bidang_ssw }}</p>
          </div>
          @endif
          @if($applicant->level_bahasa_jepang)
          <div>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Level Bahasa Jepang</p>
            <p class="text-slate-800 font-bold">{{ $applicant->level_bahasa_jepang }}</p>
          </div>
          @endif
          @if($applicant->ipk)
          <div>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">IPK</p>
            <p class="text-slate-800 font-bold">{{ $applicant->ipk }}</p>
          </div>
          @endif
          @if($applicant->shift_kursus)
          <div>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Pilihan Shift</p>
            <p class="text-slate-800 font-bold capitalize">{{ $applicant->shift_kursus }}</p>
          </div>
          @endif
        </div>
      </div>
      @endif

      {{-- Dokumen --}}
      <div class="bg-white p-8 rounded-2xl border border-slate-200 shadow-sm">
        <h4 class="font-bold text-slate-800 mb-6 flex items-center gap-2">
          <span class="material-symbols-outlined text-primary">folder_open</span> Dokumen Unggahan
        </h4>
        @if($applicant->document)
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @php
              $docs = [
                'KTP' => $applicant->document->ktp,
                'KK' => $applicant->document->kk,
                'Pas Foto' => $applicant->document->foto,
                'Ijazah' => $applicant->document->ijazah,
                'Sertifikat' => $applicant->document->sertifikat,
              ];
            @endphp
            @foreach($docs as $label => $path)
              @if($path)
                <div class="flex items-center justify-between p-4 bg-slate-50 rounded-xl border border-slate-100">
                  <div class="flex items-center gap-3">
                    <span class="material-symbols-outlined text-slate-400">description</span>
                    <span class="text-sm font-bold text-slate-700">{{ $label }}</span>
                  </div>
                  <a href="{{ Storage::url($path) }}" target="_blank" class="flex items-center gap-1 text-xs font-bold text-primary hover:underline">
                    Lihat <span class="material-symbols-outlined text-xs">open_in_new</span>
                  </a>
                </div>
              @endif
            @endforeach
          </div>
        @else
          <div class="py-12 text-center">
            <span class="material-symbols-outlined text-4xl text-slate-200">cloud_off</span>
            <p class="text-slate-400 text-sm mt-2">Pendaftar ini belum mengunggah dokumen.</p>
          </div>
        @endif
      </div>
    </div>

    <div class="space-y-8">
      {{-- Status Pendaftaran --}}
      <div class="bg-white p-8 rounded-2xl border border-slate-200 shadow-sm">
        <h4 class="font-bold text-slate-800 mb-6">Status Seleksi</h4>
        <form action="{{ route('admin.applicants.updateStatus', $applicant) }}" method="POST" class="space-y-4">
          @csrf @method('PATCH')
          <div>
            <select name="status_seleksi" class="w-full rounded-xl border-slate-200 text-sm font-bold mb-4">
              <option value="baru" {{ $applicant->status_seleksi === 'baru' ? 'selected' : '' }}>Baru</option>
              <option value="review" {{ $applicant->status_seleksi === 'review' ? 'selected' : '' }}>Review</option>
              <option value="interview" {{ $applicant->status_seleksi === 'interview' ? 'selected' : '' }}>Interview</option>
              <option value="lolos" {{ $applicant->status_seleksi === 'lolos' ? 'selected' : '' }}>Lolos</option>
              <option value="tidak_lolos" {{ $applicant->status_seleksi === 'tidak_lolos' ? 'selected' : '' }}>Tidak Lolos</option>
            </select>
            <button type="submit" class="w-full py-3 bg-primary text-white font-bold rounded-xl shadow-lg shadow-primary/20 hover:bg-primary/90 transition-all">
              Update Status
            </button>
          </div>
        </form>
      </div>

      {{-- Info Batch --}}
      <div class="bg-primary/5 p-8 rounded-2xl border border-primary/10">
        <h4 class="font-bold text-primary mb-4">Informasi Batch</h4>
        <div class="space-y-4">
          <div>
            <p class="text-[10px] font-bold text-primary/60 uppercase tracking-widest mb-1">Program</p>
            <p class="text-slate-800 font-bold">{{ $applicant->program->nama_program }}</p>
          </div>
          <div>
            <p class="text-[10px] font-bold text-primary/60 uppercase tracking-widest mb-1">Gelombang / Batch</p>
            <p class="text-slate-800 font-bold">{{ $applicant->batch->nama_batch }}</p>
          </div>
          <div class="pt-4 border-t border-primary/10">
            <p class="text-xs text-primary/80 leading-relaxed font-medium">Pendaftar ini terdaftar pada batch yang sedang <strong>{{ $applicant->batch->status }}</strong>.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
