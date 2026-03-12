<nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme" id="layout-navbar">
  <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
    <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
      <i class="bx bx-menu bx-sm"></i>
    </a>
  </div>
    <style>
    /* Styling Container Dropdown */
    .dropdown-menu {
        border: none;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        border-radius: 12px;
        padding: 0.75rem;
        min-width: 230px;
    }

    /* Efek Hover pada Item Dropdown */
    .dropdown-item {
        border-radius: 8px;
        padding: 0.6rem 1rem;
        transition: all 0.2s ease;
        color: #566a7f;
    }

    .dropdown-item:hover {
        background-color: rgba(105, 108, 255, 0.08); /* Warna ungu khas Sneat */
        color: #696cff !important;
        transform: translateX(5px); /* Geser sedikit ke kanan saat hover */
    }

    /* Icon di dalam Dropdown */
    .dropdown-item i {
        font-size: 1.1rem;
        vertical-align: middle;
        transition: transform 0.2s ease;
    }

    .dropdown-item:hover i {
        transform: scale(1.1);
    }

    /* Divider yang lebih tipis dan bersih */
    .dropdown-divider {
        margin: 0.5rem 0;
        border-color: #f0f2f4;
    }

    /* Styling Header Profil dalam Dropdown */
    .dropdown-user-header {
        padding: 0.5rem 1rem;
        margin-bottom: 0.5rem;
    }

    .dropdown-user-header .fw-semibold {
        color: #566a7f;
        font-size: 0.95rem;
    }

    /* Styling Avatar di Navbar agar lebih rapi */
    .avatar img {
        border: 2px solid #fff;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
</style>
  <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
    <div class="navbar-nav align-items-center">
      <div class="nav-item d-flex align-items-center">
        <i class="bx bx-search fs-4 lh-0"></i>
        <input type="text" class="form-control border-0 shadow-none" placeholder="Search..." aria-label="Search..." />
      </div>
    </div>
    <ul class="navbar-nav flex-row align-items-center ms-auto">
      <li class="nav-item navbar-dropdown dropdown-user dropdown">
        <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
          <div class="avatar avatar-online">
            {{-- Menampilkan Foto Profil di Lingkaran Kecil Navbar --}}
            @if(Auth::user()->avatar)
              <img src="{{ asset('storage/avatars/' . Auth::user()->avatar) }}?t={{ time() }}" alt class="w-px-40 h-auto rounded-circle" style="object-fit: cover; height: 40px !important;" />
            @else
              <div class="rounded-circle d-flex align-items-center justify-content-center bg-primary text-white" style="width: 40px; height: 40px; font-weight: bold;">
                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
              </div>
            @endif
          </div>
        </a>
        <ul class="dropdown-menu dropdown-menu-end">
          <li>
            <a class="dropdown-item" href="{{ route('profile.index') }}">
              <div class="d-flex">
                <div class="flex-shrink-0 me-3">
                  <div class="avatar avatar-online">
                    @if(Auth::user()->avatar)
                      <img src="{{ asset('storage/avatars/' . Auth::user()->avatar) }}?t={{ time() }}" alt class="w-px-40 h-auto rounded-circle" style="object-fit: cover; height: 40px !important;" />
                    @else
                      <div class="rounded-circle d-flex align-items-center justify-content-center bg-primary text-white" style="width: 40px; height: 40px; font-weight: bold;">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                      </div>
                    @endif
                  </div>
                </div>
                <div class="flex-grow-1">
                  {{-- Nama dan Role Dinamis --}}
                  <span class="fw-semibold d-block">{{ Auth::user()->name }}</span>
                  <small class="text-muted">{{ ucfirst(Auth::user()->role) }}</small>
                </div>
              </div>
            </a>
          </li>
          <li>
            <div class="dropdown-divider"></div>
          </li>
          <li>
            <a class="dropdown-item" href="{{ route('profile.index') }}">
              <i class="bx bx-user me-2"></i>
              <span class="align-middle">Profile</span>
            </a>
          </li>
          <li>
            <div class="dropdown-divider"></div>
          </li>
          <li>
            <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
              <i class="bx bx-power-off me-2"></i>
              <span class="align-middle">Log Out</span>
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
              @csrf
            </form>
          </li>
        </ul>
      </li>
      </ul>
  </div>
</nav>