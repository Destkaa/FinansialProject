<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default" data-assets-path="{{ asset('assets') }}/">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Riwayat Aktivitas | Sneat</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/favicon/favicon.ico') }}" />
    
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/boxicons.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/core.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/theme-default.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}" />
    <script src="{{ asset('assets/vendor/js/helpers.js') }}"></script>
    <script src="{{ asset('assets/js/config.js') }}"></script>

    <style>
    /* Modern & Minimalist Button dengan Animasi */
    .btn-clear-history {
        background-color: #fff;
        color: #ff3e1d;
        border: 1px solid #ff3e1d;
        transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        border-radius: 6px;
        font-weight: 500;
        position: relative;
        overflow: hidden;
    }

    .btn-clear-history:hover {
        background-color: #ff3e1d;
        color: #fff !important;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(255, 62, 29, 0.25) !important;
    }

    /* Animasi denyut halus saat tombol muncul/idle */
    .btn-clear-history i {
        transition: transform 0.3s ease;
    }

    .btn-clear-history:hover i {
        transform: scale(1.2);
    }

    /* Efek riak/pulse pada border (opsional, sangat elegan) */
    @keyframes pulse-border {
        0% { box-shadow: 0 0 0 0 rgba(255, 62, 29, 0.4); }
        70% { box-shadow: 0 0 0 10px rgba(255, 62, 29, 0); }
        100% { box-shadow: 0 0 0 0 rgba(255, 62, 29, 0); }
    }

    .btn-clear-history:active {
        transform: scale(0.95);
    }

    /* Selebihnya tetap sama */
    .table thead th {
        background-color: #f8f9fa;
        text-transform: uppercase;
        font-size: 0.7rem;
        letter-spacing: 0.5px;
        font-weight: 700;
        color: #566a7f;
    }
    /* ... styling lainnya ... */
    </style>
  </head>

  <body>
    <div class="layout-wrapper layout-content-navbar">
      <div class="layout-container">
        
        @include('layouts.components.sidebar')

        <div class="layout-page">
          @include('layouts.components.navbar')

          <div class="content-wrapper">
            <div class="container-xxl flex-grow-1 container-p-y">
                
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h4 class="fw-bold mb-1">Riwayat Aktivitas</h4>
                        <p class="text-muted small mb-0">Pemantauan log sistem secara real-time</p>
                    </div>
                    
                    @if(auth()->user()->role == 'admin')
                    <form action="{{ route('history.clear') }}" method="POST" onsubmit="return confirm('Hapus seluruh riwayat aktivitas?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-clear-history btn-sm px-3 shadow-none">
                            <i class="bx bx-trash-alt me-1"></i> 
                            <span class="d-none d-sm-inline-block">Bersihkan Riwayat</span>
                        </button>
                    </form>
                    @endif
                </div>

                @if(session('success'))
                <div class="alert alert-primary alert-dismissible shadow-none border-0 mb-4" role="alert" style="background-color: #e7e7ff; color: #696cff;">
                    <div class="d-flex align-items-center">
                        <i class="bx bx-info-circle me-2"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                <div class="card border-0 shadow-sm">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th style="width: 15%">Waktu</th>
                                    <th style="width: 15%">Pengguna</th>
                                    <th style="width: 12%">Role</th>
                                    <th style="width: 12%">Aksi</th>
                                    <th>Keterangan</th>
                                    <th style="width: 12%">IP Address</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($logs as $item)
                                <tr>
                                    <td>
                                        <div class="fw-semibold text-dark">{{ $item->created_at->translatedFormat('d M Y') }}</div>
                                        <div class="small text-muted">{{ $item->created_at->format('H:i:s') }}</div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-xs me-2">
                                                <span class="avatar-initial rounded-circle bg-label-secondary text-secondary" style="font-size: 0.6rem;">
                                                    <i class="bx bx-user"></i>
                                                </span>
                                            </div>
                                            <span class="text-dark">{{ $item->user->name ?? 'System' }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge badge-soft {{ ($item->user->role ?? '') == 'admin' ? 'bg-label-primary' : 'bg-label-secondary' }}">
                                            {{ ucfirst($item->user->role ?? 'System') }}
                                        </span>
                                    </td>
                                    <td>
                                        @php
                                            $badgeClass = 'bg-label-info';
                                            $action = strtoupper($item->activity);
                                            if($action == 'HAPUS') $badgeClass = 'bg-label-danger';
                                            elseif($action == 'UPDATE') $badgeClass = 'bg-label-warning';
                                            elseif($action == 'TAMBAH') $badgeClass = 'bg-label-success';
                                        @endphp
                                        <span class="badge badge-soft {{ $badgeClass }}">
                                            {{ $action }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="log-description small">{{ $item->description }}</div>
                                    </td>
                                    <td>
                                        <span class="ip-text">{{ $item->ip_address ?? '-' }}</span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <p class="text-muted mb-0">Tidak ada data aktivitas.</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    @if($logs->hasPages())
                    <div class="card-footer bg-transparent border-top py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="small text-muted">Total {{ $logs->total() }} records</span>
                            {{ $logs->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            @include('layouts.components.footer')
            <div class="content-backdrop fade"></div>
          </div>
        </div>
      </div>
    </div>

    <script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/popper/popper.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/menu.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>
  </body>
</html>