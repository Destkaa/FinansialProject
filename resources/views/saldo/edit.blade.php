<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Edit Data Saldo | Sneat</title>

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
                <div class="col-xxl">
                  <div class="card mb-4 shadow-sm">
                    <div class="card-header d-flex align-items-center justify-content-between">
                      <h5 class="mb-0">Edit Data Saldo</h5>
                      <small class="text-muted float-end">Perbarui informasi saldo</small>
                    </div>
                    <div class="card-body">
                      <form action="{{ route('saldo.update', $saldo->id) }}" method="POST">
                        @csrf
                        @method('PUT') <div class="row mb-3">
                          <label class="col-sm-2 col-form-label" for="e_wallet">E Wallet</label>
                          <div class="col-sm-10">
                            <div class="input-group input-group-merge">
                              <span class="input-group-text"><i class="bx bx-wallet"></i></span>
                              <input 
                                type="text" 
                                name="e_wallet" 
                                class="form-control" 
                                id="e_wallet" 
                                value="{{ $saldo->e_wallet }}" 
                                placeholder="Contoh: Dana, GoPay"
                                required
                              />
                            </div>
                          </div>
                        </div>

                        <div class="row mb-3">
                          <label class="col-sm-2 col-form-label" for="stok">Total</label>
                          <div class="col-sm-10">
                            <div class="input-group input-group-merge">
                              <span class="input-group-text"><i class="bx bx-dollar-circle"></i></span>
                              <input 
                                type="number" 
                                name="stok" 
                                class="form-control" 
                                id="stok" 
                                value="{{ $saldo->stok }}" 
                                placeholder="Masukkan jumlah saldo"
                                required
                              />
                            </div>
                          </div>
                        </div>

                        <div class="row justify-content-center mt-4">
                          <div class="col-sm-10 d-flex gap-2">
                            <button type="submit" class="btn btn-primary px-4 shadow-sm">Simpan Perubahan</button>
                            <a href="{{ route('saldo.index') }}" class="btn btn-outline-secondary px-4">Batal</a>
                          </div>
                        </div>
                      </form>
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
    <script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>
  </body>
</html>