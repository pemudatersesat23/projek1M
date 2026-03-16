<!DOCTYPE html>
<html class="light" lang="id">
<head>
  <meta charset="UTF-8"/>
  <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('admin-title', 'Dashboard') — Admin | LPK Kizuku</title>
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
      <div class="p-6 flex items-center gap-3">
        <div class="w-10 h-10 rounded-lg bg-primary flex items-center justify-center">
          <span class="text-white font-bold text-lg">K</span>
        </div>
        <div>
          <h1 class="font-bold text-primary leading-tight">LPK Kizuku</h1>
          <p class="text-xs text-slate-500">Admin Panel</p>
        </div>
      </div>

      <nav class="flex-1 mt-4 px-3 space-y-1">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors group {{ request()->routeIs('admin.dashboard') ? 'sidebar-item-active' : 'text-slate-600 hover:bg-slate-50' }}">
          <span class="material-symbols-outlined text-[22px]">dashboard</span>
          <span class="font-medium text-sm">Dashboard</span>
        </a>
        <a href="{{ route('admin.siswa.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors group {{ request()->routeIs('admin.siswa.index') || request()->routeIs('admin.siswa.show') ? 'sidebar-item-active' : 'text-slate-600 hover:bg-slate-50' }}">
          <span class="material-symbols-outlined text-[22px]">group</span>
          <span class="font-medium text-sm">Data Siswa</span>
        </a>
        <a href="{{ route('admin.siswa.create') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors group {{ request()->routeIs('admin.siswa.create') || request()->routeIs('admin.siswa.edit') ? 'sidebar-item-active' : 'text-slate-600 hover:bg-slate-50' }}">
          <span class="material-symbols-outlined text-[22px]">person_add</span>
          <span class="font-medium text-sm">Tambah Siswa</span>
        </a>
        <a href="{{ route('admin.berita.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors group {{ request()->routeIs('admin.berita.*') ? 'sidebar-item-active' : 'text-slate-600 hover:bg-slate-50' }}">
          <span class="material-symbols-outlined text-[22px]">newspaper</span>
          <span class="font-medium text-sm">Berita</span>
        </a>
        <a href="{{ route('admin.payment.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors group {{ request()->routeIs('admin.payment.*') ? 'sidebar-item-active' : 'text-slate-600 hover:bg-slate-50' }}">
          <span class="material-symbols-outlined text-[22px]">payments</span>
          <span class="font-medium text-sm">Payment</span>
        </a>
        <a href="{{ route('admin.export') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors group {{ request()->routeIs('admin.export') ? 'sidebar-item-active' : 'text-slate-600 hover:bg-slate-50' }}">
          <span class="material-symbols-outlined text-[22px]">download</span>
          <span class="font-medium text-sm">Export</span>
        </a>
        <a href="{{ route('admin.partner-campus.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors group {{ request()->routeIs('admin.partner-campus.*') ? 'sidebar-item-active' : 'text-slate-600 hover:bg-slate-50' }}">
          <span class="material-symbols-outlined text-[22px]">school</span>
          <span class="font-medium text-sm">Kampus Partner</span>
        </a>
        <div class="pt-2 pb-1 px-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Manajemen Program</div>
        <a href="{{ route('admin.programs.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors group {{ request()->routeIs('admin.programs.*') ? 'sidebar-item-active' : 'text-slate-600 hover:bg-slate-50' }}">
          <span class="material-symbols-outlined text-[22px]">assignment</span>
          <span class="font-medium text-sm">Data Program</span>
        </a>
        <a href="{{ route('admin.batches.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors group {{ request()->routeIs('admin.batches.*') ? 'sidebar-item-active' : 'text-slate-600 hover:bg-slate-50' }}">
          <span class="material-symbols-outlined text-[22px]">layers</span>
          <span class="font-medium text-sm">Batch Pendaftaran</span>
        </a>
        <a href="{{ route('admin.applicants.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors group {{ request()->routeIs('admin.applicants.*') ? 'sidebar-item-active' : 'text-slate-600 hover:bg-slate-50' }}">
          <span class="material-symbols-outlined text-[22px]">recent_actors</span>
          <span class="font-medium text-sm">Data Pendaftar</span>
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
        {{-- Flash Messages --}}
        @if(session('success'))
          <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 flex items-center gap-3">
            <span class="material-symbols-outlined text-emerald-600">check_circle</span>
            <span class="text-sm font-medium text-emerald-700">{{ session('success') }}</span>
          </div>
        @endif

        @if(session('error'))
          <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 flex items-center gap-3">
            <span class="material-symbols-outlined text-red-600">error</span>
            <span class="text-sm font-medium text-red-700">{{ session('error') }}</span>
          </div>
        @endif

        @yield('admin-content')
      </div>
    </main>
  </div>

  <script>
    function toggleSidebar() {
      document.getElementById('sidebar').classList.toggle('open');
      document.getElementById('sidebarOverlay').classList.toggle('active');
    }
  </script>
  @yield('admin-scripts')
</body>
</html>
