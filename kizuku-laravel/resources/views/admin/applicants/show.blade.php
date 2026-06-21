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
        @php
            $findAnswer = function($keywords) use ($applicant) {
                foreach ($applicant->dynamicAnswers as $ans) {
                    $snapLabel = $ans->field_label_snapshot;
                    $labelStr = strtolower(is_array($snapLabel) ? ($snapLabel['id'] ?? '') : (string) $snapLabel);
                    foreach ((array)$keywords as $kw) {
                        if (str_contains($labelStr, $kw)) {
                            // Resolve label
                            $val = $ans->value;
                            $optionsSnap = $ans->field_options_snapshot ?? $ans->formField?->options;
                            if (is_string($optionsSnap)) {
                                $optionsSnap = json_decode($optionsSnap, true);
                            }
                            if (is_array($optionsSnap)) {
                                foreach ($optionsSnap as $opt) {
                                    if (isset($opt['value']) && $opt['value'] == $val) {
                                        return $opt['label']['id'] ?? $val;
                                    }
                                }
                            }
                            return is_array($val) ? implode(', ', $val) : $val;
                        }
                    }
                }
                return null;
            };

            $jk = $applicant->jenis_kelamin ?? $findAnswer(['kelamin', 'gender']);
            $tempatLahir = $applicant->tempat_lahir ?? $findAnswer(['tempat', 'pob']);
            $pendidikan = $applicant->pendidikan ?? $findAnswer(['pendidikan', 'education', 'terakhir']);
            $alamat = $applicant->alamat ?? $findAnswer(['alamat', 'domisili', 'address']);
            $pengalamanKerja = $applicant->pengalaman_kerja ?: ($applicant->pengalaman ?: $findAnswer(['pengalaman', 'experience', 'kerja', 'riwayat']));
            $motivasi = $applicant->motivasi ?: $findAnswer(['motivasi', 'alasan', 'tujuan', 'motivation']);
        @endphp
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Nama Lengkap</p>
            <p class="text-slate-800 font-bold">{{ $applicant->nama }}</p>
          </div>
          <div>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Jenis Kelamin</p>
            <p class="text-slate-800 font-bold">{{ $jk ? ($jk === 'L' ? 'Laki-laki' : ($jk === 'P' ? 'Perempuan' : ucfirst($jk))) : '-' }}</p>
          </div>
          <div>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Tempat, Tgl Lahir</p>
            <p class="text-slate-800 font-bold">{{ $tempatLahir ?: '-' }}, {{ $applicant->tanggal_lahir?->format('d/m/Y') ?? '-' }}</p>
          </div>
          <div>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Pendidikan Terakhir</p>
            <p class="text-slate-800 font-bold">{{ $pendidikan ?: '-' }}</p>
          </div>
          <div class="md:col-span-2">
            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Alamat Lengkap</p>
            <p class="text-slate-800 font-medium leading-relaxed">{{ $alamat ?: '-' }}</p>
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
            <p class="text-slate-700 text-sm whitespace-pre-line">{{ $pengalamanKerja ?: 'Tidak ada pengalaman kerja dicantumkan.' }}</p>
          </div>
          <div class="p-4 bg-slate-50 rounded-xl">
            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Motivasi</p>
            <p class="text-slate-700 text-sm whitespace-pre-line">{{ $motivasi ?: 'Tidak ada motivasi dicantumkan.' }}</p>
          </div>
        </div>
      </div>

      {{-- Informasi Tambahan (Spesifik Program) --}}
      @if($applicant->additional_data || $applicant->tinggi_badan || $applicant->bidang_ssw || $applicant->ipk || $applicant->shift_kursus)
      <div class="bg-white p-8 rounded-2xl border border-slate-200 shadow-sm">
        <h4 class="font-bold text-slate-800 mb-6 flex items-center gap-2">
          <span class="material-symbols-outlined text-primary">assignment_ind</span> Informasi Tambahan (Program)
        </h4>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          {{-- Legacy/Standard Specialized Fields --}}
          @if($applicant->tinggi_badan)
          <div>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Tinggi / Berat Badan</p>
            <p class="text-slate-800 font-bold">{{ $applicant->tinggi_badan }} cm / {{ $applicant->berat_badan }} kg</p>
          </div>
          @endif
          @if($applicant->bidang_ssw)
          <div>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Bidang SSW</p>
            <p class="text-slate-800 font-bold">{{ $applicant->bidang_ssw }}</p>
          </div>
          @endif
          @if($applicant->ipk)
          <div>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">IPK</p>
            <p class="text-slate-800 font-bold">{{ $applicant->ipk }}</p>
          </div>
          @endif

          {{-- Additional Data JSON --}}
          @if($applicant->additional_data)
            @foreach($applicant->additional_data as $key => $value)
              @if(!in_array($key, ['agreement_truth', 'agreement_selection', 'agreement_kendari']))
                <div>
                  <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">{{ str_replace('_', ' ', ucfirst($key)) }}</p>
                  <p class="text-slate-800 font-bold">
                    @if(is_array($value))
                      {{ implode(', ', array_map(fn($v) => ucfirst(str_replace('_', ' ', $v)), $value)) }}
                    @else
                      {{ ucfirst(str_replace('_', ' ', $value)) }}
                    @endif
                  </p>
                </div>
              @endif
            @endforeach
            
            {{-- Agreements Section --}}
            <div class="col-span-full pt-4 mt-4 border-t border-slate-100 flex flex-wrap gap-4">
               @if(isset($applicant->additional_data['agreement_truth']))
                <span class="flex items-center gap-1 text-[10px] font-bold text-green-600 bg-green-50 px-2 py-1 rounded">
                   <span class="material-symbols-outlined text-sm">check_circle</span> Data Benar
                </span>
               @endif
               @if(isset($applicant->additional_data['agreement_selection']))
                <span class="flex items-center gap-1 text-[10px] font-bold text-green-600 bg-green-50 px-2 py-1 rounded">
                   <span class="material-symbols-outlined text-sm">check_circle</span> Siap Pelatihan
                </span>
               @endif
               @if(isset($applicant->additional_data['agreement_kendari']))
                <span class="flex items-center gap-1 text-[10px] font-bold text-green-600 bg-green-50 px-2 py-1 rounded">
                   <span class="material-symbols-outlined text-sm">check_circle</span> Siap Seleksi Offline
                </span>
               @endif
            </div>
          @endif
        </div>
      </div>
      @endif


      {{-- Data Formulir Dinamis Terstruktur --}}
      @if($applicant->form && $applicant->form->fields->isNotEmpty())
        @php
            // Ambil semua field form, siapkan dictionary jawaban dan file
            $answersDict = $applicant->dynamicAnswers->keyBy('form_field_id');
            $filesDict = $applicant->dynamicFiles->keyBy('form_field_id');
            
            // Group fields by section sesuai urutan form
            $sections = [];
            $currentSection = null;
            
            foreach($applicant->form->fields as $field) {
                if ($field->type === 'section') {
                    $currentSection = $field;
                    $sections[$field->id] = ['section' => $field, 'items' => []];
                } else {
                    $sectionId = $currentSection ? $currentSection->id : 'default';
                    if (!isset($sections[$sectionId])) {
                        $sections[$sectionId] = ['section' => null, 'items' => []];
                    }
                    $ans = $answersDict->get($field->id);
                    $file = $filesDict->get($field->id);
                    if ($ans || $file) {
                        $sections[$sectionId]['items'][] = [
                            'field' => $field,
                            'answer' => $ans,
                            'file' => $file,
                        ];
                    }
                }
            }
        @endphp
        
        <div class="bg-white p-8 rounded-2xl border border-slate-200 shadow-sm">
          <h4 class="font-bold text-slate-800 mb-6 flex items-center gap-2">
            <span class="material-symbols-outlined text-primary">dynamic_form</span>
            Data Formulir Pendaftar
          </h4>
          
          <div class="space-y-8">
            @forelse($sections as $sectionData)
              @if(count($sectionData['items']) > 0)
                <div class="border border-slate-100 rounded-xl overflow-hidden">
                  @if($sectionData['section'])
                    <div class="bg-slate-50 px-6 py-4 border-b border-slate-100">
                      <h5 class="font-bold text-slate-700">{{ $sectionData['section']->label }}</h5>
                      @if($sectionData['section']->description)
                        <p class="text-xs text-slate-500 mt-1 leading-relaxed">{{ $sectionData['section']->description }}</p>
                      @endif
                    </div>
                  @else
                    <div class="bg-slate-50 px-6 py-4 border-b border-slate-100">
                      <h5 class="font-bold text-slate-700">Informasi Umum</h5>
                    </div>
                  @endif
                  
                  <div class="p-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($sectionData['items'] as $item)
                      @if($item['file'])
                        {{-- RENDER FILE --}}
                        @php
                          $snapLabel = $item['file']->field_label_snapshot;
                          $fileLabel = is_array($snapLabel) ? ($snapLabel['id'] ?? ($snapLabel[array_key_first($snapLabel)] ?? $item['field']->label)) : (string) $snapLabel;
                        @endphp
                        <div class="col-span-full lg:col-span-1">
                          <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">{{ $fileLabel }}</p>
                          <div class="flex items-center justify-between p-3 bg-slate-50 rounded-xl border border-slate-100 hover:border-primary/30 transition-colors">
                            <div class="flex items-start gap-3 overflow-hidden">
                              <span class="material-symbols-outlined text-slate-400 mt-0.5">description</span>
                              <div class="truncate">
                                <p class="text-sm font-bold text-slate-700 truncate" title="{{ $item['file']->original_name }}">{{ $item['file']->original_name }}</p>
                                <p class="text-xs text-slate-400">{{ $item['file']->readableSize() }}</p>
                              </div>
                            </div>
                            <a href="{{ route('admin.applicants.dynamic-files.download', [$applicant, $item['file']]) }}" class="flex items-center gap-1 text-xs font-bold text-primary hover:underline whitespace-nowrap ml-2">
                              <span class="material-symbols-outlined text-sm">download</span> Unduh
                            </a>
                          </div>
                        </div>
                      @elseif($item['answer'])
                        {{-- RENDER TEXT ANSWER --}}
                        @php
                          $snapLabel = $item['answer']->field_label_snapshot;
                          $displayLabel = is_array($snapLabel) ? ($snapLabel['id'] ?? ($snapLabel[array_key_first($snapLabel)] ?? $item['field']->label)) : (string) $snapLabel;
                          
                          $rawVal = $item['answer']->value;
                          $optionsSnap = $item['answer']->field_options_snapshot ?? $item['field']->options;
                          if (is_string($optionsSnap)) {
                              $optionsSnap = json_decode($optionsSnap, true);
                          }
                          
                          $mapValueToLabel = function($v) use ($optionsSnap) {
                              if (is_array($optionsSnap)) {
                                  foreach ($optionsSnap as $opt) {
                                      if (isset($opt['value']) && $opt['value'] == $v) {
                                          return $opt['label']['id'] ?? ($opt['label']['jp'] ?? $v);
                                      }
                                  }
                              }
                              return $v;
                          };

                          if (is_array($rawVal)) {
                              $val = array_map($mapValueToLabel, $rawVal);
                          } else {
                              $val = $mapValueToLabel($rawVal);
                          }
                          
                          $isLongText = in_array($item['field']->type, ['textarea', 'address']);
                        @endphp
                        <div class="{{ $isLongText ? 'col-span-full' : '' }}">
                          <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">{{ $displayLabel }}</p>
                          <p class="text-slate-800 {{ $isLongText ? 'font-medium leading-relaxed whitespace-pre-line text-sm' : 'font-bold' }}">
                            @if(is_array($val))
                              {{ implode(', ', $val) }}
                            @else
                              {{ $val ?: '—' }}
                            @endif
                          </p>
                        </div>
                      @endif
                    @endforeach
                  </div>
                </div>
              @endif
            @empty
              <div class="py-8 text-center border border-slate-100 rounded-xl">
                <span class="material-symbols-outlined text-3xl text-slate-200">inbox</span>
                <p class="text-slate-400 text-sm mt-2">Tidak ada data formulir yang diisi.</p>
              </div>
            @endforelse
          </div>
        </div>
      @else
        {{-- FALLBACK TO FLAT LAYOUT IF FORM ASSOCIATION IS MISSING --}}
        <div class="bg-white p-8 rounded-2xl border border-slate-200 shadow-sm">
          <h4 class="font-bold text-slate-800 mb-6 flex items-center gap-2">
            <span class="material-symbols-outlined text-primary">dynamic_form</span>
            Jawaban Formulir Dinamis
          </h4>
          @if($applicant->dynamicAnswers->isNotEmpty())
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
              @foreach($applicant->dynamicAnswers as $answer)
                @php
                  $snapLabel = $answer->field_label_snapshot;
                  $displayLabel = is_array($snapLabel) ? ($snapLabel['id'] ?? ($snapLabel[array_key_first($snapLabel)] ?? 'Field')) : (string) $snapLabel;
                  
                  $rawVal = $answer->value;
                  $optionsSnap = $answer->field_options_snapshot ?? $answer->formField?->options;
                  if (is_string($optionsSnap)) {
                      $optionsSnap = json_decode($optionsSnap, true);
                  }
                  
                  $mapValueToLabel = function($v) use ($optionsSnap) {
                      if (is_array($optionsSnap)) {
                          foreach ($optionsSnap as $opt) {
                              if (isset($opt['value']) && $opt['value'] == $v) {
                                  return $opt['label']['id'] ?? ($opt['label']['jp'] ?? $v);
                              }
                          }
                      }
                      return $v;
                  };

                  $val = is_array($rawVal) ? array_map($mapValueToLabel, $rawVal) : $mapValueToLabel($rawVal);
                @endphp
                <div>
                  <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">{{ $displayLabel }}</p>
                  <p class="text-slate-800 font-bold">
                    {{ is_array($val) ? implode(', ', $val) : ($val ?: '—') }}
                  </p>
                </div>
              @endforeach
            </div>
          @else
            <div class="py-8 text-center">
              <span class="material-symbols-outlined text-3xl text-slate-200">inbox</span>
              <p class="text-slate-400 text-sm mt-2">Tidak ada jawaban formulir dinamis.</p>
            </div>
          @endif
        </div>

        @if($applicant->dynamicFiles->isNotEmpty())
        <div class="bg-white p-8 rounded-2xl border border-slate-200 shadow-sm mt-8">
          <h4 class="font-bold text-slate-800 mb-6 flex items-center gap-2">
            <span class="material-symbols-outlined text-primary">upload_file</span>
            Dokumen Dinamis
          </h4>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach($applicant->dynamicFiles as $dynFile)
              @php
                $snapLabel = $dynFile->field_label_snapshot;
                $fileLabel = is_array($snapLabel) ? ($snapLabel['id'] ?? ($snapLabel[array_key_first($snapLabel)] ?? 'Dokumen')) : (string) $snapLabel;
              @endphp
              <div class="flex items-center justify-between p-4 bg-slate-50 rounded-xl border border-slate-100">
                <div class="flex items-start gap-3">
                  <span class="material-symbols-outlined text-slate-400 mt-0.5">description</span>
                  <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">{{ $fileLabel }}</p>
                    <p class="text-sm font-bold text-slate-700 mt-0.5">{{ $dynFile->original_name }}</p>
                    <p class="text-xs text-slate-400">{{ $dynFile->readableSize() }}</p>
                  </div>
                </div>
                <a href="{{ route('admin.applicants.dynamic-files.download', [$applicant, $dynFile]) }}" class="flex items-center gap-1 text-xs font-bold text-primary hover:underline whitespace-nowrap">
                  <span class="material-symbols-outlined text-sm">download</span> Unduh
                </a>
              </div>
            @endforeach
          </div>
        </div>
        @endif
      @endif

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

      {{-- Info Batch & Form --}}
      <div class="bg-primary/5 p-8 rounded-2xl border border-primary/10 space-y-6">
        <div>
          <h4 class="font-bold text-primary mb-4">Informasi Batch</h4>
          <div class="space-y-4">
            <div>
              <p class="text-[10px] font-bold text-primary/60 uppercase tracking-widest mb-1">Program</p>
              <p class="text-slate-800 font-bold">{{ $applicant->program?->nama_program ?? '-' }}</p>
            </div>
            <div>
              <p class="text-[10px] font-bold text-primary/60 uppercase tracking-widest mb-1">Gelombang / Batch</p>
              <p class="text-slate-800 font-bold">{{ $applicant->batch?->nama_batch ?? '-' }}</p>
            </div>
            <div>
              <p class="text-[10px] font-bold text-primary/60 uppercase tracking-widest mb-1">Skema</p>
              <p class="text-slate-800 font-bold">{{ $applicant->programSchema?->nama_skema ?? 'Umum' }}</p>
            </div>
          </div>
        </div>

        @if($applicant->form_id)
        <div class="pt-6 border-t border-primary/10">
          <h4 class="font-bold text-primary mb-4">Informasi Formulir</h4>
          <div class="space-y-4">
            <div>
              <p class="text-[10px] font-bold text-primary/60 uppercase tracking-widest mb-1">Judul Formulir (Snapshot)</p>
              <p class="text-slate-800 font-bold">
                {{ is_array($applicant->form_title_snapshot) ? ($applicant->form_title_snapshot['id'] ?? ($applicant->form_title_snapshot['en'] ?? '—')) : ($applicant->form_title_snapshot ?: '—') }}
              </p>
            </div>
            <div>
              <p class="text-[10px] font-bold text-primary/60 uppercase tracking-widest mb-1">Versi</p>
              <p class="text-slate-800 font-bold">v{{ $applicant->form_version_snapshot }}</p>
            </div>
          </div>
        </div>
        @endif

        <div class="pt-4 border-t border-primary/10">
          <p class="text-xs text-primary/80 leading-relaxed font-medium">Pendaftar ini terdaftar pada batch yang sedang <strong>{{ $applicant->batch?->status ?? '-' }}</strong>.</p>
        </div>
      </div>
    </div>
  </div>
@endsection
