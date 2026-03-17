<div class="nav-user">
    <!-- User Dropdown Button -->
    <button class="user-btn" onclick="toggleUserMenu()">
        <span class="user-name">{{ Auth::user()->name }}</span>
        <svg class="dropdown-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
        </svg>
    </button>

    <!-- User Dropdown Menu -->
    <div class="user-menu" id="userMenu">
        <a href="{{ route('profile.edit') }}" class="user-menu-item">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
            </svg>
            {{ __('messages.auth.profile') }}
        </a>
        @if(auth()->user()->role === 'admin')
        <a href="{{ route('admin.dashboard') }}" class="user-menu-item admin">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
            </svg>
            {{ __('messages.auth.dashboard') }}
        </a>
        @endif
        <form method="POST" action="{{ route('logout') }}" class="user-menu-form">
            @csrf
            <button type="submit" class="user-menu-item logout">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd" />
                    <path fill-rule="evenodd" d="M3.293 9.293a1 1 0 011.414 0L9 13.586l4.293-4.293a1 1 0 111.414 1.414l-5 5a1 1 0 01-1.414 0l-5-5a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
                {{ __('messages.auth.logout') }}
            </button>
        </form>
    </div>
</div>

<script>
    function toggleUserMenu() {
        const btn = document.querySelector('.user-btn');
        const menu = document.getElementById('userMenu');
        btn.classList.toggle('active');
        menu.classList.toggle('active');
    }
    // Close menu when clicking outside
    document.addEventListener('click', function(event) {
        const userBtn = document.querySelector('.user-btn');
        const userMenu = document.getElementById('userMenu');
        if (userBtn && userMenu && !userBtn.contains(event.target) && !userMenu.contains(event.target)) {
            userBtn.classList.remove('active');
            userMenu.classList.remove('active');
        }
    });
</script>
