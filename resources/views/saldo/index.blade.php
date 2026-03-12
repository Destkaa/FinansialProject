<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default" data-assets-path="{{ asset('assets') }}/" data-template="vertical-menu-template-free">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Data Saldo | Destkaa</title>

    <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/favicon/favicon.ico') }}" />

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet" />

    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/boxicons.css') }}" />

    <link rel="stylesheet" href="{{ asset('assets/vendor/css/core.css') }}" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/theme-default.css') }}" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}" />

    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/apex-charts/apex-charts.css') }}" />

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
                        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Tables /</span> Data Saldo</h4>

                        <div class="d-flex justify-content-start gap-2">
                            <a href="{{ route('saldo.create') }}" class="btn btn-primary my-4">
                                <i class="bx bx-folder-plus me-1"></i> Tambah Data
                            </a>
                            <a href="{{ route('export.saldo') }}" class="btn btn-outline-success my-4">
                                <i class="bx bx-spreadsheet me-1"></i> Export Excel
                            </a>
                        </div>

                        <div class="card border-0 shadow-sm">
                            <h5 class="card-header fw-bold">Daftar Saldo E-Wallet</h5>
                            <div class="table-responsive text-nowrap">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>No</th>
                                            <th>E-Wallet</th>
                                            @if(Auth::user()->role == 'admin')
                                                <th>Pemilik</th>
                                            @endif
                                            <th>Total Saldo</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="table-border-bottom-0">
                                        @php $no = 1; @endphp
                                        @forelse ( $saldo as $data )
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td>
                                                <i class="bx bx-wallet text-primary me-2"></i>
                                                <span class="fw-medium">{{ $data->nama_e_wallet }}</span>
                                            </td>
                                            @if(Auth::user()->role == 'admin')
                                                <td><span class="badge bg-label-info">{{ $data->user->name ?? 'N/A' }}</span></td>
                                            @endif
                                            <td class="fw-bold text-dark">
                                                Rp {{ number_format($data->total, 0, ',', '.') }}
                                            </td>
                                            <td>
                                                <div class="dropdown">
                                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                                        <i class="bx bx-dots-vertical-rounded"></i>
                                                    </button>
                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item" href="{{ route('saldo.edit', $data->id) }}">
                                                            <i class="bx bx-edit-alt me-1 text-warning"></i> Edit
                                                        </a>
                                                        <a class="dropdown-item" href="{{ route('saldo.show', $data->id) }}">
                                                            <i class="bx bx-show me-1 text-info"></i> Show
                                                        </a>
                                                        <form action="{{ route('saldo.destroy', $data->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus saldo ini? Tindakan ini tidak dapat dibatalkan.')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="dropdown-item text-danger">
                                                                <i class="bx bx-trash me-1"></i> Delete
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="{{ Auth::user()->role == 'admin' ? '5' : '4' }}" class="text-center p-4">
                                                <div class="text-muted small">Belum ada data saldo tersedia.</div>
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
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

    <script async defer src="https://buttons.github.io/buttons.js"></script>
</body>
</html>