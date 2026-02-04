<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <title>Edit Uang Masuk | Sneat</title>
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
                        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Transaksi /</span> Edit Uang Masuk</h4>

                        <div class="card mb-4">
                            <div class="card-body">
                                <form action="{{ route('uangmasuk.update', $uangmasuk->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')

                                    <div class="row mb-3">
                                        <label class="col-sm-2 col-form-label">Nominal</label>
                                        <div class="col-sm-10">
                                            <div class="input-group input-group-merge">
                                                <span class="input-group-text"><i class="bx bx-money"></i></span>
                                                <input type="text" class="form-control" id="nominal_input" value="Rp {{ number_format($uangmasuk->nominal, 0, ',', '.') }}" required />
                                                <input type="hidden" name="nominal" id="nominal_asli" value="{{ $uangmasuk->nominal }}">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label class="col-sm-2 col-form-label">E-Wallet</label>
                                        <div class="col-sm-10">
                                            <div class="input-group input-group-merge">
                                                <span class="input-group-text"><i class="bx bx-wallet"></i></span>
                                                <select name="id_saldo" class="form-select" required>
                                                    @foreach($saldo as $s)
                                                        <option value="{{ $s->id }}" {{ $uangmasuk->id_saldo == $s->id ? 'selected' : '' }}>
                                                            {{ $s->nama_e_wallet }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label class="col-sm-2 col-form-label">Keterangan</label>
                                        <div class="col-sm-10">
                                            <textarea name="keterangan" class="form-control" required>{{ $uangmasuk->keterangan }}</textarea>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label class="col-sm-2 col-form-label">Tanggal</label>
                                        <div class="col-sm-10">
                                            <input type="date" name="tanggal_uang_masuk" class="form-control" value="{{ $uangmasuk->tanggal_uang_masuk }}" required />
                                        </div>
                                    </div>

                                    <div class="row justify-content-end">
                                        <div class="col-sm-10">
                                            <button type="submit" class="btn btn-primary">Update Data</button>
                                            <a href="{{ route('uangmasuk.index') }}" class="btn btn-outline-secondary">Batal</a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
    <script>
        const inputTampilan = document.getElementById('nominal_input');
        const inputAsli = document.getElementById('nominal_asli');

        inputTampilan.addEventListener('input', function(e) {
            let angka = this.value.replace(/[^0-9]/g, '');
            inputAsli.value = angka;
            this.value = angka ? 'Rp ' + new Intl.NumberFormat('id-ID').format(angka) : '';
        });
    </script>
</body>
</html>