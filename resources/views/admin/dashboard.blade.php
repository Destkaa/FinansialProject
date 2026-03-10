<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default" data-assets-path="{{ asset('assets') }}/">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Dashboard | Finansial</title>
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
        
        {{-- Pastikan file ini ada di resources/views/layouts/components/sidebar.blade.php --}}
        @include('layouts.components.sidebar')

        <div class="layout-page">
          
          {{-- Pastikan file ini ada di resources/views/layouts/components/navbar.blade.php --}}
          @include('layouts.components.navbar')

          <div class="content-wrapper">
            <div class="container-xxl flex-grow-1 container-p-y">
                <div class="row">
                    
                    <div class="col-lg-12 mb-4">
                        <div class="card bg-label-primary border-0">
                            <div class="d-flex align-items-end row">
                                <div class="col-sm-7">
                                    <div class="card-body">
                                        <h5 class="card-title text-primary">Selamat Datang, Admin {{ Auth::user()->name }}! 🛡️</h5>
                                        <p class="mb-4">Sistem berjalan normal. Anda memantau total <strong>{{ $totalUser }}</strong> pengguna aktif hari ini.</p>
                                    </div>
                                </div>
                                <div class="col-sm-5 text-center text-sm-left">
                                    <div class="card-body pb-0 px-0 px-md-4 text-end">
                                        <img src="{{ asset('assets/img/illustrations/man-with-laptop-light.png') }}" height="140" alt="View Badge User">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 mb-4">
                        <div class="card shadow-none bg-label-info border-0">
                            <div class="card-body text-center">
                                <div class="avatar mx-auto mb-3">
                                    <span class="badge bg-white p-3 rounded-circle"><i class="bx bx-dollar fs-3 text-info"></i></span>
                                </div>
                                <span class="d-block mb-1 text-dark fw-semibold">Total Saldo Global</span>
                                <h4 class="card-title text-info fw-bold">Rp {{ number_format($totalSaldo, 0, ',', '.') }}</h4>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 mb-4">
                        <div class="card shadow-none bg-label-success border-0">
                            <div class="card-body text-center">
                                <div class="avatar mx-auto mb-3">
                                    <span class="badge bg-white p-3 rounded-circle"><i class="bx bx-wallet fs-3 text-success"></i></span>
                                </div>
                                <span class="d-block mb-1 text-dark fw-semibold">Pemasukan Global</span>
                                <h4 class="card-title text-success fw-bold">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</h4>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 mb-4">
                        <div class="card shadow-none bg-label-danger border-0">
                            <div class="card-body text-center">
                                <div class="avatar mx-auto mb-3">
                                    <span class="badge bg-white p-3 rounded-circle"><i class="bx bx-cart fs-3 text-danger"></i></span>
                                </div>
                                <span class="d-block mb-1 text-dark fw-semibold">Pengeluaran Global</span>
                                <h4 class="card-title text-danger fw-bold">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</h4>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="card-title m-0 fw-bold text-primary">Log 5 Transaksi Terakhir (Seluruh User)</h5>
                            </div>
                            <div class="table-responsive text-nowrap">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>User</th>
                                            <th>Jenis</th>
                                            <th>Nominal</th>
                                            <th>Keterangan</th>
                                            <th>Waktu</th>
                                        </tr>
                                    </thead>
                                    <tbody class="table-border-bottom-0">
                                        @forelse($transactions as $item)
                                        <tr>
                                            <td><span class="fw-bold">{{ $item->user->name ?? 'System' }}</span></td>
                                            <td>
                                                <span class="badge bg-label-{{ $item->tipe == 'masuk' ? 'success' : 'danger' }}">
                                                    {{ strtoupper($item->tipe) }}
                                                </span>
                                            </td>
                                            <td class="{{ $item->tipe == 'masuk' ? 'text-success' : 'text-danger' }} fw-bold">
                                                {{ $item->tipe == 'masuk' ? '+' : '-' }} Rp {{ number_format($item->nominal, 0, ',', '.') }}
                                            </td>
                                            <td>{{ $item->keterangan }}</td>
                                            <td>{{ $item->created_at->diffForHumans() }}</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="5" class="text-center p-4 text-muted">Belum ada aktivitas transaksi di sistem.</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
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

      <div class="layout-overlay layout-menu-toggle"></div>
    </div>

    <script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/popper/popper.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/menu.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>
  </body>
</html>