<!DOCTYPE html>
<html
  lang="en"
  class="light-style layout-menu-fixed"
  dir="ltr"
  data-theme="theme-default"
  data-assets-path="../assets/"
  data-template="vertical-menu-template-free"
>
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <title>Edit Data Saldo | Sneat</title>

    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/boxicons.css') }}" />

    <link rel="stylesheet" href="{{asset('assets/vendor/css/core.css')}}" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{asset('assets/vendor/css/theme-default.css')}}" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{asset('assets/css/demo.css')}}" />

    <script src="{{asset('assets/vendor/js/helpers.js')}}"></script>
    <script src="{{asset('assets/js/config.js')}}"></script>
  </head>

  <body>
    <div class="layout-wrapper layout-content-navbar">
      <div class="layout-container">
        @include('layouts.components.sidebar')

        <div class="layout-page">
          @include('layouts.components.navbar')

          <div class="content-wrapper">
            <div class="container-xxl flex-grow-1 container-p-y">
              <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Tables /</span> Edit Data Saldo</h4>

              <div class="row">
                <div class="col-xxl">
                  <div class="card mb-4">
                    <div class="card-header d-flex align-items-center justify-content-between">
                      <h5 class="mb-0">Perbarui Informasi Saldo</h5>
                    </div>
                    <div class="card-body">
                      <form action="{{ route('saldo.update', $saldo->id) }}" method="POST">
                        @csrf
                        @method('PUT') 
                        
                        <div class="row mb-3">
                          <label class="col-sm-2 col-form-label" for="nama_e_wallet">E Wallet</label>
                          <div class="col-sm-10">
                            <div class="input-group input-group-merge">
                              <span class="input-group-text"><i class="bx bx-wallet"></i></span>
                              <input 
                                type="text" 
                                class="form-control" 
                                name="nama_e_wallet" 
                                id="nama_e_wallet" 
                                value="{{ $saldo->nama_e_wallet }}" 
                                placeholder="Contoh: Dana / GoPay" 
                                required
                              />
                            </div>
                          </div>
                        </div>

                        <div class="row mb-3">
                          <label class="col-sm-2 col-form-label" for="total">Total</label>
                          <div class="col-sm-10">
                            <div class="input-group input-group-merge">
                              <span class="input-group-text"><i class="bx bx-money"></i></span>
                              <input 
                                type="text" 
                                class="form-control" 
                                id="total_input" 
                                value="Rp {{ number_format($saldo->total, 0, ',', '.') }}" 
                                placeholder="Masukkan Total Saldo" 
                                required
                              />
                              <input type="hidden" name="total" id="total_asli" value="{{ $saldo->total }}">
                            </div>
                          </div>
                        </div>

                        <div class="row justify-content-end">
                          <div class="col-sm-10">
                            <button type="submit" class="btn btn-primary px-4">Simpan Perubahan</button>
                            <a href="{{ route('saldo.index') }}" class="btn btn-outline-secondary px-4 ms-2">Batal</a>
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
    </div>

    <script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>

    {{-- Script untuk memfungsikan format Rp agar data bisa terupdate --}}
    <script>
      const inputTampilan = document.getElementById('total_input');
      const inputAsli = document.getElementById('total_asli');

      inputTampilan.addEventListener('input', function(e) {
        let angka = this.value.replace(/[^0-9]/g, '');
        inputAsli.value = angka;
        if (angka) {
          this.value = 'Rp ' + new Intl.NumberFormat('id-ID').format(angka);
        } else {
          this.value = '';
        }
      });
    </script>
  </body>
</html>