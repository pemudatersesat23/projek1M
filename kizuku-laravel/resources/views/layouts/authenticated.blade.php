<!doctype html>
<html lang="id">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'LPK Kizuku International Academy')</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;600;700;800&family=Noto+Sans+JP:wght@400;700&display=swap" rel="stylesheet">

  <!-- CSS per Section -->
  <link rel="stylesheet" href="{{ asset('css/variables.css') }}">
  <link rel="stylesheet" href="{{ asset('css/navbar.css') }}">
  <link rel="stylesheet" href="{{ asset('css/buttons.css') }}">
  <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
  @stack('styles')
</head>
<body>

  <!-- ═══ NAVBAR ADMIN ═══ -->
  @auth
    @include('layouts.navigation')
  @endauth

  <!-- ═══ MAIN CONTENT ═══ -->
  @yield('content')

  <!-- FAB Admin -->
  @auth
    <a class="btn btn-primary admin-fab" href="{{ route('admin.siswa.index') }}">⚙️ Admin Panel</a>
  @endauth

  <script src="{{ asset('js/navbar.js') }}"></script>
  @stack('scripts')

</body>
</html>
