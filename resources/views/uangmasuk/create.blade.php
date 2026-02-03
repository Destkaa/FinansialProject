<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <title>Tambah Uang Masuk | Sneat</title>

    <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/favicon/favicon.ico') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/boxicons.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/core.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/theme-default.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />

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
                        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Transaksi /</span> Tambah Uang Masuk</h4>

                        <div class="card mb-4">
                            <div class="card-header d-flex align-items-center justify-content-between">
                                <h5 class="mb-0">Form Pemasukan</h5>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('uangmasuk.store') }}" method="POST">
                                    @csrf
                                    
                                    <div class="row mb-3">
                                        <label class="col-sm-2 col-form-label" for="nominal">Nominal</label>
                                        <div class="col-sm-10">
                                            <div class="input-group input-group-merge">
                                                <span class="input-group-text"><i class="bx bx-money"></i></span>
                                                <input 
                                                    type="number" 
                                                    name="nominal" 
                                                    id="nominal" 
                                                    class="form-control @error('nominal') is-invalid @enderror" 
                                                    placeholder="Masukkan nominal..." 
                                                    value="{{ old('nominal') }}" 
                                                    required 
                                                />
                                            </div>
                                            @error('nominal') <small class="text-danger">{{ $message }}</small> @enderror
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label class="col-sm-2 col-form-label" for="id_saldo">Pilih E-Wallet</label>
                                        <div class="col-sm-10">
                                            <div class="input-group input-group-merge">
                                                <span class="input-group-text"><i class="bx bx-wallet"></i></span>
                                                <select name="id_saldo" id="id_saldo" class="form-select @error('id_saldo') is-invalid @enderror" required>
                                                    <option value="" selected disabled>-- Pilih Akun Saldo --</option>
                                                    @foreach($saldo as $item)
                                                        <option value="{{ $item->id }}" {{ old('id_saldo') == $item->id ? 'selected' : '' }}>
                                                            {{ $item->nama_e_wallet }} — (Rp {{ number_format($item->jumlah_saldo, 0, ',', '.') }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            @error('id_saldo') <small class="text-danger">{{ $message }}</small> @enderror
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label class="col-sm-2 col-form-label" for="keterangan">Keterangan</label>
                                        <div class="col-sm-10">
                                            <textarea name="keterangan" id="keterangan" class="form-control" rows="2" placeholder="Contoh: Gaji Januari" required>{{ old('keterangan') }}</textarea>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label class="col-sm-2 col-form-label" for="tanggal">Tanggal</label>
                                        <div class="col-sm-10">
                                            <input type="date" name="tanggal_uang_masuk" id="tanggal" class="form-control" value="{{ old('tanggal_uang_masuk', date('Y-m-d')) }}" required />
                                        </div>
                                    </div>

                                    <div class="row justify-content-end">
                                        <div class="col-sm-10">
                                            <button type="submit" class="btn btn-primary">Simpan Data</button>
                                            <a href="{{ route('uangmasuk.index') }}" class="btn btn-outline-secondary">Batal</a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @include('layouts.components.footer')
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/menu.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>
</body>
</html>