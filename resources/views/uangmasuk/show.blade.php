<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Detail Uang Masuk | Sneat</title>

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
              <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Transaksi /</span> Detail Uang Masuk</h4>

              <div class="row">
                <div class="col-xxl">
                  <div class="card mb-4 shadow-sm">
                    <div class="card-header d-flex align-items-center justify-content-between">
                      <h5 class="mb-0">Informasi Lengkap Pemasukan</h5>
                    </div>
                    <div class="card-body">
                      
                      <div class="row mb-3">
                        <label class="col-sm-2 col-form-label">Nominal</label>
                        <div class="col-sm-10">
                          <div class="input-group input-group-merge">
                            <span class="input-group-text"><i class="bx bx-money"></i></span>
                            <input type="text" class="form-control bg-light" value="Rp {{ number_format($uangmasuk->nominal, 0, ',', '.') }}" readonly />
                          </div>
                        </div>
                      </div>

                      <div class="row mb-3">
                        <label class="col-sm-2 col-form-label">Keterangan</label>
                        <div class="col-sm-10">
                          <div class="input-group input-group-merge">
                            <span class="input-group-text"><i class="bx bx-comment"></i></span>
                            <textarea class="form-control bg-light" readonly rows="3">{{ $uangmasuk->keterangan }}</textarea>
                          </div>
                        </div>
                      </div>

                      <div class="row mb-3">
                        <label class="col-sm-2 col-form-label">Tanggal Masuk</label>
                        <div class="col-sm-10">
                          <div class="input-group input-group-merge">
                            <span class="input-group-text"><i class="bx bx-calendar"></i></span>
                            <input type="text" class="form-control bg-light" value="{{ \Carbon\Carbon::parse($uangmasuk->tanggal_uang_masuk)->translatedFormat('d F Y') }}" readonly />
                          </div>
                        </div>
                      </div>

                      <div class="row justify-content-end mt-4">
                        <div class="col-sm-10">
                          <a href="{{ route('uangmasuk.edit', $uangmasuk->id) }}" class="btn btn-primary px-4">
                            <i class="bx bx-edit-alt me-1"></i> Edit Transaksi
                          </a>
                          <a href="{{ route('uangmasuk.index') }}" class="btn btn-outline-secondary px-4 ms-2">Kembali</a>
                        </div>
                      </div>
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
    <script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>
  </body>
</html>