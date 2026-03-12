<style>
    /* 1. Sidebar Active Style & Glow Effect */
    .menu-vertical .menu-item.active > .menu-link:not(.menu-toggle) {
        background: linear-gradient(72deg, rgba(105, 108, 255, 0.16) 0%, rgba(105, 108, 255, 0.04) 100%) !important;
        border-right: 4px solid #696cff;
        font-weight: 600;
        position: relative;
    }

    /* Efek cahaya halus di pinggir menu aktif */
    .menu-vertical .menu-item.active > .menu-link::after {
        content: '';
        position: absolute;
        right: -4px;
        top: 15%;
        height: 70%;
        width: 6px;
        background: #696cff;
        filter: blur(4px);
        animation: smoothPulse 3s ease-in-out infinite;
    }

    @keyframes smoothPulse {
        0%, 100% { opacity: 0.7; transform: scaleY(1); }
        50% { opacity: 0.3; transform: scaleY(0.8); }
    }

    /* 2. Submenu Indicator Dot Premium */
    .menu-sub .menu-item.active .menu-link::before {
        background-color: #696cff !important;
        transform: scale(1.5);
        box-shadow: 0 0 12px rgba(105, 108, 255, 0.6);
    }

    /* 3. ULTRA SMOOTH SUBMENU TRANSITION */
    .menu-sub {
        display: block !important;
        max-height: 0;
        overflow: hidden;
        opacity: 0;
        transform: translateY(-12px) scale(0.98);
        transform-origin: top;
        /* Transisi terpisah untuk durasi berbeda agar lebih organik */
        transition: 
            max-height 0.5s cubic-bezier(0.645, 0.045, 0.355, 1),
            opacity 0.4s ease,
            transform 0.45s cubic-bezier(0.23, 1, 0.32, 1) !important;
    }

    .menu-item.open > .menu-sub {
        max-height: 600px; /* Nilai aman yang cukup besar */
        opacity: 1;
        transform: translateY(0) scale(1);
        margin-top: 4px;
        margin-bottom: 8px;
    }

    /* 4. Link & Hover Interaction dengan geseran halus */
    .menu-item .menu-link {
        transition: all 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    }

    .menu-item .menu-link:hover {
        background-color: rgba(105, 108, 255, 0.05);
        padding-left: 1.4rem !important;
    }

    /* 5. Profile Box Premium Animation */
    .user-profile-sidebar {
        transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
    }
    .user-profile-sidebar:hover {
        background: rgba(105, 108, 255, 0.08) !important;
        transform: translateY(-4px);
        box-shadow: 0 10px 25px rgba(105, 108, 255, 0.12);
        border-color: rgba(105, 108, 255, 0.3) !important;
    }

    /* Rotasi Icon Panah yang Elegan */
    .menu-item .menu-toggle::after {
        transition: transform 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55) !important;
    }
</style>

<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="{{ route('home') }}" class="app-brand-link">
            <span class="app-brand-logo demo">
                <svg width="25" viewBox="0 0 25 42" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M13.7918663,0.358365126 L3.39788168,7.44174259 C0.566865006,9.69408886 -0.379795268,12.4788597 0.557900856,15.7960551 C0.68998853,16.2305145 1.09562888,17.7872135 3.12357076,19.2293357 C3.8146334,19.7207684 5.32369333,20.3834223 7.65075054,21.2172976 L7.59773219,21.2525164 L2.63468769,24.5493413 C0.445452254,26.3002124 0.0884951797,28.5083815 1.56381646,31.1738486 C2.83770406,32.8170431 5.20850219,33.2640127 7.09180128,32.5391584 C8.347334,32.0559211 11.4559176,30.0011079 16.4175519,26.3747182 C18.0338572,24.4997857 18.6973423,22.4544883 18.4080071,20.2388261 C17.963753,17.5346866 16.1776345,15.5799961 13.0496516,14.3747546 L10.9194936,13.4715819 L18.6192054,7.984237 L13.7918663,0.358365126 Z" fill="#696cff" />
                </svg>
            </span>
            <span class="app-brand-text demo menu-text fw-bolder ms-2 text-capitalize">Destkaa</span>
        </a>
        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <div class="px-4 py-3 mb-2">
        <div class="user-profile-sidebar d-flex align-items-center p-3 rounded-3" style="background: rgba(105, 108, 255, 0.05); border: 1px solid rgba(105, 108, 255, 0.1);">
            <div class="avatar avatar-online me-3">
                @if(Auth::user()->avatar)
                    <img src="{{ asset('storage/avatars/' . Auth::user()->avatar) }}?t={{ time() }}" alt="Profile" class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover; border: 2px solid #fff;" />
                @else
                    <div class="rounded-circle d-flex align-items-center justify-content-center bg-primary text-white" style="width: 40px; height: 40px; font-weight: bold; border: 2px solid #fff;">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                @endif
            </div>
            <div class="d-flex flex-column overflow-hidden">
                <span class="fw-bold d-block text-truncate text-dark" style="max-width: 110px; font-size: 0.9rem;">{{ Auth::user()->name }}</span>
                <div class="d-flex align-items-center">
                    <span class="badge {{ strtolower(Auth::user()->role) == 'admin' ? 'bg-danger' : 'bg-primary' }} p-0 me-1" style="width: 7px; height: 7px; border-radius: 50%;"></span>
                    <small class="text-muted text-capitalize" style="font-size: 0.75rem;">{{ Auth::user()->role }}</small>
                </div>
            </div>
        </div>
    </div>

    <ul class="menu-inner py-1">
        <li class="menu-item {{ request()->routeIs('home') ? 'active' : '' }}">
            <a href="{{ route('home') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-home-circle"></i>
                <div>Dashboard</div>
            </a>
        </li>

        <li class="menu-header small text-uppercase"><span class="menu-header-text">Keuangan & Kas</span></li>

        <li class="menu-item {{ request()->routeIs('saldo.*') || request()->routeIs('uangmasuk.*') || request()->routeIs('uangkeluar.*') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-transfer-alt"></i>
                <div>Tables</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ request()->routeIs('saldo.*') ? 'active' : '' }}">
                    <a href="{{ route('saldo.index') }}" class="menu-link"><div>Saldo</div></a>
                </li>
                <li class="menu-item {{ request()->routeIs('uangmasuk.*') ? 'active' : '' }}">
                    <a href="{{ route('uangmasuk.index') }}" class="menu-link"><div>Pemasukan</div></a>
                </li>
                <li class="menu-item {{ request()->routeIs('uangkeluar.*') ? 'active' : '' }}">
                    <a href="{{ route('uangkeluar.index') }}" class="menu-link"><div>Pengeluaran</div></a>
                </li>
            </ul>
        </li>

        @if(Auth::user()->role == 'admin')
        <li class="menu-header small text-uppercase"><span class="menu-header-text">Laporan & Audit</span></li>
        <li class="menu-item {{ request()->routeIs('history.index') ? 'active' : '' }}">
            <a href="{{ route('history.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-history"></i>
                <div>Log Aktivitas</div>
            </a>
        </li>
        <li class="menu-item">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-cloud-download"></i>
                <div>Rekap Laporan</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item">
                    <a href="{{ route('export.uangmasuk') }}" class="menu-link">
                        <i class="menu-icon tf-icons bx bxs-file-export text-success" style="font-size: 0.8rem;"></i>
                        <div>Excel Pemasukan</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="{{ route('export.uangkeluar') }}" class="menu-link">
                        <i class="menu-icon tf-icons bx bxs-file-export text-danger" style="font-size: 0.8rem;"></i>
                        <div>Excel Pengeluaran</div>
                    </a>
                </li>
            </ul>
        </li>
        @endif

        <li class="menu-header small text-uppercase mt-4"><span class="menu-header-text">Akun</span></li>
        <li class="menu-item">
            <a href="{{ route('logout') }}" class="menu-link text-danger" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="menu-icon tf-icons bx bx-power-off"></i>
                <div class="fw-bold">Log Out</div>
            </a>
        </li>
    </ul>
</aside>

<form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Toggle Dropdown Smooth
        const toggles = document.querySelectorAll('.menu-toggle');
        toggles.forEach(toggle => {
            toggle.addEventListener('click', function(e) {
                e.preventDefault();
                const parent = this.parentElement;
                
                // Accordion logic: Tutup yang lain pas buka satu
                if (!parent.classList.contains('open')) {
                    const siblingOpen = document.querySelector('.menu-item.open');
                    if (siblingOpen && siblingOpen !== parent) {
                        siblingOpen.classList.remove('open');
                    }
                }

                parent.classList.toggle('open');
            });
        });

        // Deteksi menu aktif saat refresh halaman
        const activeSub = document.querySelector('.menu-sub .active');
        if (activeSub) {
            const parentItem = activeSub.closest('.menu-item.menu-item'); // Cari parent menu-item terdekat
            const grandParent = activeSub.closest('.menu-sub').parentElement;
            if (grandParent) grandParent.classList.add('open');
        }
    });
</script>