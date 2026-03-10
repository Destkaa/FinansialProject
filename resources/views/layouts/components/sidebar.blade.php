<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
  <div class="app-brand demo">
    <a href="{{ route('home') }}" class="app-brand-link">
      <span class="app-brand-logo demo">
        <svg width="25" viewBox="0 0 25 42" version="1.1" xmlns="http://www.w3.org/2000/svg">
          <defs>
            <path d="M13.7918663,0.358365126 L3.39788168,7.44174259 C0.566865006,9.69408886 -0.379795268,12.4788597 0.557900856,15.7960551 C0.68998853,16.2305145 1.09562888,17.7872135 3.12357076,19.2293357 C3.8146334,19.7207684 5.32369333,20.3834223 7.65075054,21.2172976 L7.59773219,21.2525164 L2.63468769,24.5493413 C0.445452254,26.3002124 0.0884951797,28.5083815 1.56381646,31.1738486 C2.83770406,32.8170431 5.20850219,33.2640127 7.09180128,32.5391584 C8.347334,32.0559211 11.4559176,30.0011079 16.4175519,26.3747182 C18.0338572,24.4997857 18.6973423,22.4544883 18.4080071,20.2388261 C17.963753,17.5346866 16.1776345,15.5799961 13.0496516,14.3747546 L10.9194936,13.4715819 L18.6192054,7.984237 L13.7918663,0.358365126 Z" id="path-1"></path>
          </defs>
          <g id="g-app-brand" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
            <g id="Brand-Logo" transform="translate(-27.000000, -15.000000)">
              <g id="Icon" transform="translate(27.000000, 15.000000)">
                <g id="Mask" transform="translate(0.000000, 8.000000)">
                  <mask id="mask-2" fill="white"><use xlink:href="#path-1"></use></mask>
                  <use fill="#696cff" xlink:href="#path-1"></use>
                </g>
              </g>
            </g>
          </g>
        </svg>
      </span>
      <span class="app-brand-text demo menu-text fw-bolder ms-2 text-capitalize">Destkaa</span>
    </a>

    <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
      <i class="bx bx-chevron-left bx-sm align-middle"></i>
    </a>
  </div>

  <div class="menu-inner-shadow"></div>

  <div class="px-4 py-3 d-flex align-items-center">
    <div class="avatar avatar-online me-3">
      @if(Auth::user()->avatar)
        {{-- Mengambil foto dari storage, ditambah timestamp (?t=) untuk bypass cache --}}
        <img src="{{ asset('storage/avatars/' . Auth::user()->avatar) }}?t={{ time() }}" 
             alt="Profile" 
             class="w-px-40 h-auto rounded-circle" 
             style="object-fit: cover; height: 40px !important;" />
      @else
        {{-- Inisial Nama jika tidak ada foto --}}
        <div class="rounded-circle d-flex align-items-center justify-content-center bg-primary text-white shadow-sm" 
             style="width: 40px; height: 40px; font-weight: bold; font-size: 14px;">
          {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
        </div>
      @endif
    </div>
    <div class="d-flex flex-column">
      <span class="fw-bold d-block text-truncate" style="max-width: 120px; font-size: 14px;">
        {{ Auth::user()->name }}
      </span>
      <small class="text-muted text-capitalize">{{ Auth::user()->role }}</small>
    </div>
  </div>
  <ul class="menu-inner py-1">
    <li class="menu-item {{ request()->routeIs('home') ? 'active' : '' }}">
      <a href="{{ route('home') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-home-circle"></i>
        <div data-i18n="Analytics">Dashboard</div>
      </a>
    </li>

    <li class="menu-header small text-uppercase">
      <span class="menu-header-text">Transaksi & Keuangan</span>
    </li>

    <li class="menu-item {{ request()->routeIs('saldo.*') || request()->routeIs('uangmasuk.*') || request()->routeIs('uangkeluar.*') ? 'active open' : '' }}">
      <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class="menu-icon tf-icons bx bx-table"></i>
        <div data-i18n="Tables">Tables</div>
      </a>
      <ul class="menu-sub">
        <li class="menu-item {{ request()->routeIs('saldo.*') ? 'active' : '' }}">
          <a href="{{ route('saldo.index') }}" class="menu-link">
            <div data-i18n="Data Saldo">Saldo </div>
          </a>
        </li>
        <li class="menu-item {{ request()->routeIs('uangmasuk.*') ? 'active' : '' }}">
          <a href="{{ route('uangmasuk.index') }}" class="menu-link">
            <div data-i18n="Uang Masuk">Pemasukan</div>
          </a>
        </li>
        <li class="menu-item {{ request()->routeIs('uangkeluar.*') ? 'active' : '' }}">
          <a href="{{ route('uangkeluar.index') }}" class="menu-link">
            <div data-i18n="Uang Keluar">Pengeluaran</div>
          </a>
        </li>
      </ul>
    </li>

    @if(Auth::user()->role == 'admin')
    <li class="menu-header small text-uppercase">
      <span class="menu-header-text">Laporan & Audit</span>
    </li>

    <li class="menu-item {{ request()->routeIs('history.index') ? 'active' : '' }}">
      <a href="{{ route('history.index') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-history"></i>
        <div data-i18n="History">Riwayat</div>
      </a>
    </li>

    <li class="menu-item">
      <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class="menu-icon tf-icons bx bx-download"></i>
        <div data-i18n="Export">Export</div>
      </a>
      <ul class="menu-sub">
        <li class="menu-item">
          <a href="{{ route('export.uangmasuk') }}" class="menu-link">
            <div>Excel Pemasukan</div>
          </a>
        </li>
        <li class="menu-item">
          <a href="{{ route('export.uangkeluar') }}" class="menu-link">
            <div>Excel Pengeluaran</div>
          </a>
        </li>
      </ul>
    </li>
    @endif
  </ul>
</aside>