@extends('layouts.app')

@section('title', 'LPK Kizuku International Academy — Wujudkan Karier Impian di Jepang')
@section('meta_description', 'LPK Kizuku International Academy adalah lembaga pelatihan kerja ke Jepang terpercaya di Makassar. Program Tokutei Ginou, Engineering, magang Jepang, dan kursus bahasa Jepang intensif. Daftar sekarang!')
@section('meta_keywords', 'kerja ke jepang, lpk makassar, tokutei ginou, magang jepang, kursus bahasa jepang makassar, kizuku academy, pelatihan kerja jepang, lowongan kerja jepang, lpk gowa, karier jepang')

@section('content')
  @include('sections.hero')
  @include('sections.keunggulan')
  @include('sections.program')
  @include('sections.fasilitas')
  @include('sections.keunggulan-testimoni')
  @include('sections.gallery')
  @include('sections.kontak')
  @include('sections.berita')
@endsection
