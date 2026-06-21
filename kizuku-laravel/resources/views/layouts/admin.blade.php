<!DOCTYPE html>
<html class="light" lang="id">
<head>
  <meta charset="UTF-8"/>
  <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('admin-title', 'Dashboard') — Admin | LPK Kizuku</title>
  <link rel="icon" type="image/png" href="{{ asset('image/logo tab broswer.png') }}">
  <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
  <script>
    tailwind.config = {
      darkMode: "class",
      theme: {
        extend: {
          colors: {
            "primary": "#0067a3",
            "accent-red": "#E31E24",
            "background-light": "#f5f7f8",
            "background-dark": "#0f1c23",
          },
          fontFamily: {
            "display": ["Inter"]
          },
          borderRadius: { "DEFAULT": "0.25rem", "lg": "0.5rem", "xl": "0.75rem", "full": "9999px" },
        },
      },
    }
  </script>
  <style>
    body { font-family: 'Inter', sans-serif; }
    .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
    .sidebar-item-active { background-color: rgba(0, 103, 163, 0.1); color: #0067a3; border-right: 4px solid #0067a3; }
    /* Sidebar mobile overlay */
    .sidebar-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,.4); z-index: 30; }
    .sidebar-overlay.active { display: block; }
    @media (max-width: 1023px) {
      .admin-sidebar { transform: translateX(-100%); z-index: 40; transition: transform .25s ease; }
      .admin-sidebar.open { transform: translateX(0); }
    }
  </style>
  @yield('admin-styles')
</head>
<body class="bg-background-light text-slate-900 font-display">
  <div class="flex min-h-screen">

    {{-- Mobile Overlay --}}
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

    {{-- Sidebar Navigation --}}
    <aside class="admin-sidebar w-64 bg-white border-r border-slate-200 flex flex-col fixed h-full" id="sidebar">
      <div class="py-8 px-5 border-b border-slate-50 flex flex-col items-center">
        <a href="{{ route('admin.dashboard') }}" class="block">
            <img src="{{ asset('image/logo kiuzuku utama.png') }}" alt="LPK Kizuku Logo" class="h-28 w-auto object-contain">
        </a>
        <div class="mt-4 text-center">
          <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">Admin Panel Control</p>
        </div>
      </div>

      <nav class="flex-1 mt-4 px-3 space-y-1 overflow-y-auto min-h-0 pb-4">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors group {{ request()->routeIs('admin.dashboard') ? 'sidebar-item-active' : 'text-slate-600 hover:bg-slate-50' }}">
          <span class="material-symbols-outlined text-[22px]">dashboard</span>
          <span class="font-medium text-sm">Dashboard</span>
        </a>
        <a href="{{ route('admin.applicants.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors group {{ request()->routeIs('admin.applicants.index') || request()->routeIs('admin.applicants.show') ? 'sidebar-item-active' : 'text-slate-600 hover:bg-slate-50' }}">
          <span class="material-symbols-outlined text-[22px]">group</span>
          <span class="font-medium text-sm">Data Pendaftar</span>
        </a>
        <a href="{{ route('admin.berita.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors group {{ request()->routeIs('admin.berita.*') ? 'sidebar-item-active' : 'text-slate-600 hover:bg-slate-50' }}">
          <span class="material-symbols-outlined text-[22px]">newspaper</span>
          <span class="font-medium text-sm">Berita</span>
        </a>

        <a href="{{ route('admin.export') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors group {{ request()->routeIs('admin.export') ? 'sidebar-item-active' : 'text-slate-600 hover:bg-slate-50' }}">
          <span class="material-symbols-outlined text-[22px]">download</span>
          <span class="font-medium text-sm">Export</span>
        </a>
        <a href="{{ route('admin.partner-campus.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors group {{ request()->routeIs('admin.partner-campus.*') ? 'sidebar-item-active' : 'text-slate-600 hover:bg-slate-50' }}">
          <span class="material-symbols-outlined text-[22px]">school</span>
          <span class="font-medium text-sm">Partnership</span>
        </a>
        <div class="pt-2 pb-1 px-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Konten Website</div>
        <a href="{{ route('admin.hero-sections.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors group {{ request()->routeIs('admin.hero-sections.*') ? 'sidebar-item-active' : 'text-slate-600 hover:bg-slate-50' }}">
          <span class="material-symbols-outlined text-[22px]">image</span>
          <span class="font-medium text-sm">Banner Hero</span>
        </a>
        <a href="{{ route('admin.testimonials.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors group {{ request()->routeIs('admin.testimonials.*') ? 'sidebar-item-active' : 'text-slate-600 hover:bg-slate-50' }}">
          <span class="material-symbols-outlined text-[22px]">reviews</span>
          <span class="font-medium text-sm">Testimoni</span>
        </a>
        <a href="{{ route('admin.galleries.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors group {{ request()->routeIs('admin.galleries.*') ? 'sidebar-item-active' : 'text-slate-600 hover:bg-slate-50' }}">
          <span class="material-symbols-outlined text-[22px]">photo_library</span>
          <span class="font-medium text-sm">Galeri Foto</span>
        </a>
        <a href="{{ route('admin.fasilitas.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors group {{ request()->routeIs('admin.fasilitas.*') ? 'sidebar-item-active' : 'text-slate-600 hover:bg-slate-50' }}">
          <span class="material-symbols-outlined text-[22px]">apartment</span>
          <span class="font-medium text-sm">Fasilitas</span>
        </a>
        <a href="{{ route('admin.faqs.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors group {{ request()->routeIs('admin.faqs.*') ? 'sidebar-item-active' : 'text-slate-600 hover:bg-slate-50' }}">
          <span class="material-symbols-outlined text-[22px]">quiz</span>
          <span class="font-medium text-sm">FAQ Global</span>
        </a>
        <a href="{{ route('admin.keunggulans.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors group {{ request()->routeIs('admin.keunggulans.*') ? 'sidebar-item-active' : 'text-slate-600 hover:bg-slate-50' }}">
          <span class="material-symbols-outlined text-[22px]">verified_user</span>
          <span class="font-medium text-sm">Keunggulan Kizuku</span>
        </a>
        <div class="pt-2 pb-1 px-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Manajemen Program</div>
        <a href="{{ route('admin.programs.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors group {{ request()->routeIs('admin.programs.*') ? 'sidebar-item-active' : 'text-slate-600 hover:bg-slate-50' }}">
          <span class="material-symbols-outlined text-[22px]">assignment</span>
          <span class="font-medium text-sm">Data Program</span>
        </a>
        <a href="{{ route('admin.alur.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors group {{ request()->routeIs('admin.alur.*') ? 'sidebar-item-active' : 'text-slate-600 hover:bg-slate-50' }}">
          <span class="material-symbols-outlined text-[22px]">alt_route</span>
          <span class="font-medium text-sm">Alur Pendaftaran</span>
        </a>
        <a href="{{ route('admin.batches.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors group {{ request()->routeIs('admin.batches.*') ? 'sidebar-item-active' : 'text-slate-600 hover:bg-slate-50' }}">
          <span class="material-symbols-outlined text-[22px]">layers</span>
          <span class="font-medium text-sm">Batch Pendaftaran</span>
        </a>
        <a href="{{ route('admin.program-schemas.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors group {{ request()->routeIs('admin.program-schemas.*') ? 'sidebar-item-active' : 'text-slate-600 hover:bg-slate-50' }}">
          <span class="material-symbols-outlined text-[22px]">account_tree</span>
          <span class="font-medium text-sm">Skema Program</span>
        </a>
        <a href="{{ route('admin.forms.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors group {{ request()->routeIs('admin.forms.*') ? 'sidebar-item-active' : 'text-slate-600 hover:bg-slate-50' }}">
          <span class="material-symbols-outlined text-[22px]">dynamic_form</span>
          <span class="font-medium text-sm">Form Builder Baru</span>
        </a>
        <div class="pt-2 pb-1 px-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Sistem</div>
        <a href="{{ route('admin.settings.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors group {{ request()->routeIs('admin.settings.*') ? 'sidebar-item-active' : 'text-slate-600 hover:bg-slate-50' }}">
          <span class="material-symbols-outlined text-[22px]">settings</span>
          <span class="font-medium text-sm">Pengaturan</span>
        </a>
      </nav>

      <div class="p-4 border-t border-slate-200 space-y-1">
        <a href="{{ url('/') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg text-slate-600 hover:bg-slate-50 transition-colors">
          <span class="material-symbols-outlined text-[22px]">home</span>
          <span class="font-medium text-sm">Ke Website</span>
        </a>
        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 rounded-lg text-accent-red hover:bg-red-50 transition-colors">
            <span class="material-symbols-outlined text-[22px]">logout</span>
            <span class="font-medium text-sm">Logout</span>
          </button>
        </form>
      </div>
    </aside>

    {{-- Main Content --}}
    <main class="flex-1 lg:ml-64 min-h-screen">
      {{-- Top Header --}}
      <header class="h-16 bg-white border-b border-slate-200 px-4 lg:px-8 flex items-center justify-between sticky top-0 z-10">
        <div class="flex items-center gap-4">
          <button class="lg:hidden p-1 text-slate-500 hover:text-primary" onclick="toggleSidebar()">
            <span class="material-symbols-outlined">menu</span>
          </button>
          <h2 class="text-xl font-bold text-slate-800">@yield('admin-title', 'Dashboard')</h2>
          <span class="text-slate-300 hidden sm:inline">|</span>
          <a class="text-primary text-sm font-medium hover:underline hidden sm:inline" href="{{ url('/') }}">Home</a>
        </div>
        <div class="flex items-center gap-4 lg:gap-6">
          <button class="relative p-1 text-slate-500 hover:text-primary transition-colors">
            <span class="material-symbols-outlined">notifications</span>
            <span class="absolute top-0 right-0 w-2 h-2 bg-accent-red rounded-full"></span>
          </button>
          <div class="flex items-center gap-2 border-l border-slate-200 pl-4 lg:pl-6">
            <div class="w-8 h-8 rounded-full bg-primary/10 flex items-center justify-center">
              <span class="material-symbols-outlined text-primary text-lg">account_circle</span>
            </div>
            <span class="text-sm font-medium hidden sm:inline">{{ Auth::user()->name ?? 'Admin' }}</span>
          </div>
        </div>
      </header>

      {{-- Page Content --}}
      <div class="p-4 lg:p-8">
        @yield('admin-content')
      </div>
    </main>
  </div>

  @include('components.app-alerts')

  <script>
    function toggleSidebar() {
      document.getElementById('sidebar').classList.toggle('open');
      document.getElementById('sidebarOverlay').classList.toggle('active');
    }
  </script>
  @yield('admin-scripts')
</body>
</html>
