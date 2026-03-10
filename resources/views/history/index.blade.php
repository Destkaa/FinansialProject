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
  </head>

  <body>
    <div class="layout-wrapper layout-content-navbar">
      <div class="layout-container">
        
        @include('layouts.components.sidebar')

        <div class="layout-page">
          @include('layouts.components.navbar')

          <div class="content-wrapper">
            <div class="container-xxl flex-grow-1 container-p-y">
                <h4 class="fw-bold py-3 mb-4">
                    <span class="text-muted fw-light">Sistem /</span> Riwayat Aktivitas
                </h4>

                <div class="card">
                    <h5 class="card-header">Log Aktivitas {{ auth()->user()->role == 'admin' ? 'Global' : 'Saya' }}</h5>
                    <div class="table-responsive text-nowrap">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Waktu</th>
                                    @if(auth()->user()->role == 'admin')
                                        <th>User</th>
                                    @endif
                                    <th>Aksi</th>
                                    <th>Keterangan</th>
                                    <th>IP Address</th>
                                </tr>
                            </thead>
                            <tbody class="table-border-bottom-0">
                                @forelse($logs as $item)
                                <tr>
                                    <td>{{ $item->created_at->translatedFormat('d M Y, H:i') }}</td>
                                    @if(auth()->user()->role == 'admin')
                                        <td><strong>{{ $item->user->name ?? 'System' }}</strong></td>
                                    @endif
                                    <td>
                                        <span class="badge bg-label-primary">{{ strtoupper($item->action) }}</span>
                                    </td>
                                    <td>{{ $item->description }}</td>
                                    <td><small class="text-muted">{{ $item->ip_address ?? '-' }}</small></td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="{{ auth()->user()->role == 'admin' ? '5' : '4' }}" class="text-center py-4">
                                        Belum ada rekaman aktivitas.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer px-4">
                        {{-- Menampilkan navigasi halaman (Pagination) --}}
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                Menampilkan {{ $logs->firstItem() }} sampai {{ $logs->lastItem() }} dari {{ $logs->total() }} data
                            </div>
                            <div>
                                {{ $logs->links('pagination::bootstrap-5') }}
                            </div>
                        </div>
                    </div>
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