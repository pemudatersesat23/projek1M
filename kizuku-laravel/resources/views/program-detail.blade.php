@extends('layouts.app')

@section('title', $program->nama_program . ' — LPK Kizuku International Academy')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/program-detail.css') }}">
@endpush

@section('content')

  {{-- ── Hero Section ── --}}
  @include('components.program.detail.hero', [
    'program'     => $program,
    'activeBatch' => $activeBatch,
    'nextBatch'   => $nextBatch,
  ])

  {{-- ── Main Content + Batch Card ── --}}
  @include('components.program.detail.content', [
    'program'      => $program,
    'activeBatch'  => $activeBatch,
    'nextBatch'    => $nextBatch,
    'batchHistory' => $batchHistory,
  ])

  {{-- ── Registration Form (hanya jika batch aktif & bukan WA) ── --}}
  @if($activeBatch && $activeBatch->cta_type !== 'whatsapp')
    @include('components.program.detail.registration-form', [
      'program'     => $program,
      'activeBatch' => $activeBatch,
    ])
  @endif

  {{-- ── Kontak Section ── --}}
  @include('sections.kontak')

@endsection

@push('scripts')
<script src="{{ asset('assets/js/program-detail.js') }}"></script>
@endpush
