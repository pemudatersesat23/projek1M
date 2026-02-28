<nav id="navbar" class="auth-navbar">
    <a class="nav-brand" href="{{ route('dashboard') }}">
        <div class="nav-logo" aria-hidden="true"></div>
        <div class="nav-name">LPK Kizuku <span>International Academy</span></div>
    </a>

    <div class="nav-user">
        <!-- User Dropdown Button -->
        <button class="user-btn" onclick="toggleUserMenu()">
            <span class="user-name">{{ Auth::user()->name }}</span>
            <span class="user-email">{{ Auth::user()->email }}</span>
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
                {{ __('Profil') }}
            </a>
            @if(auth()->user()->role === 'admin')
            <a href="{{ route('admin.dashboard') }}" class="user-menu-item admin">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                </svg>
                {{ __('Dashboard Admin') }}
            </a>
            @endif
            <form method="POST" action="{{ route('logout') }}" class="user-menu-form">
                @csrf
                <button type="submit" class="user-menu-item logout">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd" />
                        <path fill-rule="evenodd" d="M3.293 9.293a1 1 0 011.414 0L9 13.586l4.293-4.293a1 1 0 111.414 1.414l-5 5a1 1 0 01-1.414 0l-5-5a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                    {{ __('Logout') }}
                </button>
            </form>
        </div>
    </div>

    <button class="hamburger" id="hambtn" aria-label="Menu" onclick="toggleMobileMenu()">
        <span></span><span></span><span></span>
    </button>
</nav>

<!-- Mobile Menu -->
<div class="mobile-menu" id="mobmenu">
    <div class="mob-user">
        <div class="mob-user-info">
            <div class="mob-user-name">{{ Auth::user()->name }}</div>
            <div class="mob-user-email">{{ Auth::user()->email }}</div>
        </div>
    </div>
    <a href="{{ route('profile.edit') }}" class="mob-link">⚙️ Profil</a>
    @if(auth()->user()->role === 'admin')
    <a href="{{ route('admin.dashboard') }}" class="mob-link admin">🔐 Dashboard Admin</a>
    @endif
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="mob-link logout">🚪 Logout</button>
    </form>
</div>

<style>
    /* Auth Navbar Styling */
    #navbar.auth-navbar {
        padding: 0 5vw;
        height: 68px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: rgba(255, 255, 255, .95);
        backdrop-filter: blur(18px);
        -webkit-backdrop-filter: blur(18px);
        border-bottom: 1px solid rgba(17, 17, 17, .07);
    }

    /* User Section */
    .nav-user {
        position: relative;
    }

    .user-btn {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 8px 16px;
        background: rgba(17, 17, 17, .04);
        border: 1px solid rgba(17, 17, 17, .08);
        border-radius: 12px;
        cursor: pointer;
        transition: all .3s;
        font-size: 14px;
        font-weight: 500;
        color: var(--text, #111);
    }

    .user-btn:hover {
        background: rgba(17, 17, 17, .08);
        border-color: rgba(17, 17, 17, .15);
    }

    .user-name {
        display: block;
        font-weight: 600;
        color: #111;
    }

    .user-email {
        display: none;
        font-size: 12px;
        color: #666;
        font-weight: 400;
    }

    .dropdown-icon {
        width: 16px;
        height: 16px;
        color: #666;
        transition: transform .3s;
    }

    .user-btn.active .dropdown-icon {
        transform: rotate(180deg);
    }

    /* Dropdown Menu */
    .user-menu {
        position: absolute;
        top: calc(100% + 8px);
        right: 0;
        background: white;
        border-radius: 12px;
        border: 1px solid rgba(17, 17, 17, .1);
        box-shadow: 0 8px 24px rgba(0, 0, 0, .1);
        min-width: 180px;
        opacity: 0;
        visibility: hidden;
        transform: translateY(-8px);
        transition: all .3s;
        z-index: 1000;
    }

    .user-menu.active {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
    }

    .user-menu-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 16px;
        color: #111;
        text-decoration: none;
        font-size: 14px;
        transition: all .2s;
        border: none;
        background: none;
        cursor: pointer;
        width: 100%;
        text-align: left;
    }

    .user-menu-item:first-child {
        border-top-left-radius: 12px;
        border-top-right-radius: 12px;
    }

    .user-menu-item:hover {
        background: rgba(17, 17, 17, .04);
    }

    .user-menu-item svg {
        width: 18px;
        height: 18px;
        opacity: .7;
    }

    .user-menu-item.logout {
        border-top: 1px solid rgba(17, 17, 17, .08);
        color: var(--red, #e10600);
        border-bottom-left-radius: 12px;
        border-bottom-right-radius: 12px;
    }

    .user-menu-item.admin {
        border-top: 1px solid rgba(17, 17, 17, .08);
        color: var(--red, #e10600);
        font-weight: 600;
    }

    .user-menu-item.admin:hover {
        background: rgba(225, 6, 0, .08);
    }

    .user-menu-item.admin svg {
        opacity: 1;
    }

    .user-menu-form {
        margin: 0;
    }

    /* Mobile Menu Updates */
    .mobile-menu {
        padding: 16px 5vw !important;
    }

    .mob-user {
        padding: 12px 0 16px;
        border-bottom: 1px solid rgba(17, 17, 17, .08);
        margin-bottom: 12px;
    }

    .mob-user-info {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .mob-user-name {
        font-weight: 600;
        color: #111;
        font-size: 14px;
    }

    .mob-user-email {
        font-size: 12px;
        color: #666;
    }

    .mob-link.admin {
        color: var(--red, #e10600);
        font-weight: 600;
    }

    /* Responsive */
    @media (max-width: 640px) {
        .user-btn {
            padding: 6px 12px;
            font-size: 13px;
        }

        .user-name {
            display: block;
        }

        .user-email {
            display: none;
        }

        .dropdown-icon {
            width: 14px;
            height: 14px;
        }

        .user-menu {
            min-width: 160px;
        }

        .user-menu-item {
            padding: 10px 12px;
            font-size: 13px;
        }

        .user-menu-item svg {
            width: 16px;
            height: 16px;
        }
    }
</style>

<script>
    function toggleUserMenu() {
        const btn = document.querySelector('.user-btn');
        const menu = document.getElementById('userMenu');
        
        btn.classList.toggle('active');
        menu.classList.toggle('active');
    }

    function toggleMobileMenu() {
        const menu = document.getElementById('mobmenu');
        menu.classList.toggle('active');
    }

    // Close menu when clicking outside
    document.addEventListener('click', function(event) {
        const userBtn = document.querySelector('.user-btn');
        const userMenu = document.getElementById('userMenu');
        
        if (!userBtn.contains(event.target) && !userMenu.contains(event.target)) {
            userBtn.classList.remove('active');
            userMenu.classList.remove('active');
        }
    });
</script>

