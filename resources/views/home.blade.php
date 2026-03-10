  <!DOCTYPE html>
  <html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default" data-assets-path="{{ asset('assets') }}/">
    <head>
      <meta charset="utf-8" />
      <meta name="viewport" content="width=device-width, initial-scale=1.0" />
      <title>Dashboard | Destkaa</title>
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
                  <div class="row">
                      
                      <div class="col-lg-12 mb-4">
                          <div class="card bg-label-primary border-0">
                              <div class="d-flex align-items-end row">
                                  <div class="col-sm-7">
                                      <div class="card-body">
                                          <h5 class="card-title text-primary">Selamat Datang, {{ Auth::user()->name }}! 🎉</h5>
                                          <p class="mb-4">Performa keuangan Anda terpantau aman hari ini. Terus pantau arus kas Anda!</p>
                                      </div>
                                  </div>
                                  <div class="col-sm-5 text-center text-sm-left">
                                      <div class="card-body pb-0 px-0 px-md-4">
                                          <img src="{{ asset('assets/img/illustrations/man-with-laptop-light.png') }}" height="140" alt="View Badge User">
                                      </div>
                                  </div>
                              </div>
                          </div>
                      </div>

                      <div class="col-lg-6 mb-4">
                          <div class="card shadow-none bg-label-success border-0">
                              <div class="card-body text-center">
                                  <div class="avatar mx-auto mb-3">
                                      <span class="badge bg-white p-3 rounded-circle"><i class="bx bx-wallet fs-3 text-success"></i></span>
                                  </div>
                                  <span class="d-block mb-1 text-dark fw-semibold">Total Pemasukan</span>
                                  <h4 class="card-title text-success fw-bold">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</h4>
                              </div>
                          </div>
                      </div>

                      <div class="col-lg-6 mb-4">
                          <div class="card shadow-none bg-label-danger border-0">
                              <div class="card-body text-center">
                                  <div class="avatar mx-auto mb-3">
                                      <span class="badge bg-white p-3 rounded-circle"><i class="bx bx-cart fs-3 text-danger"></i></span>
                                  </div>
                                  <span class="d-block mb-1 text-dark fw-semibold">Total Pengeluaran</span>
                                  <h4 class="card-title text-danger fw-bold">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</h4>
                              </div>
                          </div>
                      </div>

                      <div class="col-12">
                          <div class="card border-0 shadow-sm">
                              <div class="card-header d-flex justify-content-between align-items-center">
                                  <h5 class="card-title m-0 fw-bold text-primary">Riwayat Transaksi Terakhir</h5>
                              </div>
                              <div class="table-responsive text-nowrap">
                                  <table class="table table-hover">
                                      <thead class="table-light">
                                          <tr>
                                              <th>Waktu</th>
                                              <th>Keterangan</th>
                                              <th>Kategori</th>
                                              <th>Nominal</th>
                                              <th>Aksi</th>
                                          </tr>
                                      </thead>
                                      <tbody class="table-border-bottom-0">
                                          @forelse($transactions as $item)
                                          <tr>
                                              <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y, H:i') }}</td>
                                              <td><span class="fw-medium">{{ $item->keterangan }}</span></td>
                                              <td>
                                                  <span class="badge bg-label-{{ $item->kategori == 'Pemasukan' ? 'success' : 'danger' }}">
                                                      {{ $item->kategori }}
                                                  </span>
                                              </td>
                                              <td class="{{ $item->kategori == 'Pemasukan' ? 'text-success' : 'text-danger' }} fw-bold">
                                                  {{ $item->kategori == 'Pemasukan' ? '+' : '-' }} Rp {{ number_format($item->nominal, 0, ',', '.') }}
                                              </td>
                                              <td>
                                                  <div class="dropdown">
                                                      <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                                          <i class="bx bx-dots-vertical-rounded"></i>
                                                      </button>
                                                      <div class="dropdown-menu">
                                                          <form action="{{ $item->kategori == 'Pemasukan' ? route('uangmasuk.destroy', $item->id) : route('uangkeluar.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin hapus?')">
                                                              @csrf
                                                              @method('DELETE')
                                                              <button type="submit" class="dropdown-item text-danger"><i class="bx bx-trash me-1"></i> Delete</button>
                                                          </form>
                                                      </div>
                                                  </div>
                                              </td>
                                          </tr>
                                          @empty
                                          <tr>
                                              <td colspan="5" class="text-center p-4 text-muted">Belum ada data transaksi.</td>
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