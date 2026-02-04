<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <title>Data Uang Keluar | Sneat</title>
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
                        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Tables /</span> Data Uang Keluar</h4>
                        
                        @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        @endif

                        <div class="mb-4">
                            <a href="{{ route('uangkeluar.create') }}" class="btn btn-danger">
                                <i class="bx bx-folder-minus me-1"></i> Tambah Pengeluaran 
                            </a>
                        </div>

                        <div class="card">
                            <h5 class="card-header">Data Uang Keluar</h5>
                            <div class="table-responsive text-nowrap">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Nominal</th>
                                            <th>Keterangan</th>
                                            <th>Tanggal</th>
                                            <th>E Wallet</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="table-border-bottom-0">
                                        @forelse ( $uangkeluar as $data )
                                        <tr>
                                            <td><strong class="text-danger">Rp {{ number_format($data->nominal, 0, ',', '.') }}</strong></td>
                                            <td>{{ $data->keterangan }}</td>
                                            <td>{{ \Carbon\Carbon::parse($data->tanggal_uang_keluar)->format('d-m-Y') }}</td>
                                            <td>
                                                @if($data->saldo)
                                                    <span class="badge bg-label-warning">{{ $data->saldo->nama_e_wallet }}</span>
                                                @else
                                                    <span class="badge bg-label-secondary">Unknown</span>
                                                @endif
                                            </td>
                                            <td>
                                               <div class="dropdown">
                                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                                        <i class="bx bx-dots-vertical-rounded"></i>
                                                    </button>
                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item" href="{{ route('uangkeluar.edit', $data->id) }}">
                                                            <i class="bx bx-edit-alt me-1"></i> Edit
                                                        </a>
                                                        <a class="dropdown-item" href="{{ route('uangkeluar.show', $data->id) }}">
                                                            <i class="bx bx-show me-1"></i> Show
                                                        </a>
                                                        <form action="{{ route('uangkeluar.destroy', $data->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="dropdown-item">
                                                                <i class="bx bx-trash me-1"></i> Delete
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="5" class="text-center">Belum ada data uang keluar.</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
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
    <script src="{{ asset('assets/js/main.js') }}"></script>
</body>
</html>