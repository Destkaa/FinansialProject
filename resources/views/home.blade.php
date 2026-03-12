<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default" data-assets-path="{{ asset('assets') }}/">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard | Destkaa</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&family=JetBrains+Mono:wght@600&display=swap" rel="stylesheet" />

    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/boxicons.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/core.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/theme-default.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}" />
    <script src="{{ asset('assets/vendor/js/helpers.js') }}"></script>
    <script src="{{ asset('assets/js/config.js') }}"></script>

    <style>
        body { background-color: #f5f5f9; }
        
        /* 1. Card & Layout Improvements */
        .card { 
            border: none !important; 
            box-shadow: 0 2px 6px 0 rgba(67, 89, 113, 0.12) !important;
            border-radius: 0.75rem;
            transition: all 0.3s ease-in-out;
        }

        /* 2. Hover Effect */
        .card-stats:hover, .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 20px rgba(67, 89, 113, 0.15) !important;
        }

        /* 3. Clock Header Style */
        #clock-container {
            background: rgba(105, 108, 255, 0.08);
            padding: 10px 15px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            border: 1px solid rgba(105, 108, 255, 0.1);
        }
        #clock { font-family: 'JetBrains Mono', monospace; font-weight: 600; color: #696cff; }

        /* 4. Visual Borders */
        .border-top-success { border-top: 5px solid #71dd37 !important; }
        .border-top-danger { border-top: 5px solid #ff3e1d !important; }

        /* 5. Tabel Styles */
        .table thead th { 
            text-transform: uppercase; 
            font-size: 0.75rem; 
            letter-spacing: 1px; 
            background-color: #fcfcfd;
            border-bottom: 2px solid #f5f5f9;
        }
        
        .table-hover tbody tr:nth-of-type(odd) {
            background-color: rgba(105, 108, 255, 0.01);
        }

        .status-dot {
            height: 8px;
            width: 8px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 8px;
        }
        .dot-pemasukan { background-color: #71dd37; box-shadow: 0 0 8px rgba(113, 221, 55, 0.6); }
        .dot-pengeluaran { background-color: #ff3e1d; box-shadow: 0 0 8px rgba(255, 62, 29, 0.6); }
        
        .nominal-text { font-family: 'Public Sans', sans-serif; font-weight: 700; }

        .badge-role {
            font-size: 0.65rem;
            text-transform: uppercase;
            font-weight: 800;
            padding: 0.35rem 0.6rem;
            border-radius: 4px;
        }

        /* 6. Custom Scrollbar */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #696cff; border-radius: 10px; }

        /* 7. Animasi Fade In */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .container-xxl { animation: fadeIn 0.6s ease-out; }
    </style>
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
                                <div class="card bg-white">
                                    <div class="d-flex align-items-center row">
                                        <div class="col-sm-8">
                                            <div class="card-body">
                                                <h4 class="text-primary mb-1">Halo, {{ Auth::user()->name }}! 👋</h4>
                                                <p class="mb-4 text-muted">Berikut adalah ringkasan aktivitas keuangan Anda hari ini.</p>
                                                
                                                <div id="clock-container">
                                                    <i class="bx bx-time-five me-2 text-primary"></i>
                                                    <span id="clock" class="me-3">00:00:00</span>
                                                    <span id="current-date" class="small text-muted border-start ps-3">Memuat...</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-4 text-center d-none d-sm-block">
                                            <img src="{{ asset('assets/img/illustrations/man-with-laptop-light.png') }}" height="120" alt="Dashboard Image">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 mb-4">
                                <div class="card card-stats border-top-success h-100">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <span class="fw-semibold d-block mb-1 text-muted">Pemasukan</span>
                                                <h3 class="card-title mb-2 nominal-text text-success">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</h3>
                                                <small class="text-muted"><i class="bx bx-up-arrow-alt text-success"></i> Dana masuk</small>
                                            </div>
                                            <div class="avatar flex-shrink-0">
                                                <span class="avatar-initial rounded bg-label-success"><i class="bx bx-wallet"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 mb-4">
                                <div class="card card-stats border-top-danger h-100">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <span class="fw-semibold d-block mb-1 text-muted">Pengeluaran</span>
                                                <h3 class="card-title mb-2 nominal-text text-danger">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</h3>
                                                <small class="text-muted"><i class="bx bx-down-arrow-alt text-danger"></i> Dana keluar</small>
                                            </div>
                                            <div class="avatar flex-shrink-0">
                                                <span class="avatar-initial rounded bg-label-danger"><i class="bx bx-cart"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header d-flex justify-content-between align-items-center bg-transparent">
                                        <h5 class="mb-0 font-weight-bold">Riwayat Transaksi Terakhir</h5>
                                        <i class="bx bx-dots-vertical-rounded text-muted"></i>
                                    </div>
                                    <div class="table-responsive text-nowrap">
                                        <table class="table table-hover align-middle">
                                            <thead>
                                                <tr>
                                                    <th>Detail Transaksi</th>
                                                    <th>Waktu & Tanggal</th> <th>Status</th>
                                                    <th>Oleh</th>
                                                    <th>Nominal</th>
                                                    <th class="text-center">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($transactions as $item)
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="avatar avatar-sm me-3">
                                                                <span class="avatar-initial rounded-circle bg-label-secondary">
                                                                    <i class="bx {{ $item->kategori == 'Pemasukan' ? 'bx-trending-up' : 'bx-trending-down' }}"></i>
                                                                </span>
                                                            </div>
                                                            <div class="fw-bold text-dark text-truncate" style="max-width: 200px;">{{ $item->keterangan }}</div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex flex-column">
                                                            <span class="text-dark small fw-bold">
                                                                <i class="bx bx-calendar-event me-1 text-primary"></i> {{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d M Y') }}
                                                            </span>
                                                            <small class="text-muted">
                                                                <i class="bx bx-time-five me-1"></i> {{ \Carbon\Carbon::parse($item->created_at)->format('H:i') }} WIB
                                                            </small>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        @if($item->kategori == 'Pemasukan')
                                                            <span class="badge bg-label-success"><span class="status-dot dot-pemasukan"></span> Masuk</span>
                                                        @else
                                                            <span class="badge bg-label-danger"><span class="status-dot dot-pengeluaran"></span> Keluar</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <div class="d-flex flex-column">
                                                            <span class="fw-semibold text-dark small text-capitalize">{{ $item->user->name ?? 'System' }}</span>
                                                            @php
                                                                $roleColor = (strtolower($item->user->role ?? '') == 'admin') ? 'bg-label-danger' : 'bg-label-primary';
                                                            @endphp
                                                            <span class="badge {{ $roleColor }} badge-role" style="width: fit-content;">
                                                                {{ $item->user->role ?? 'User' }}
                                                            </span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <span class="nominal-text {{ $item->kategori == 'Pemasukan' ? 'text-success' : 'text-danger' }}">
                                                            {{ $item->kategori == 'Pemasukan' ? '+' : '-' }} Rp {{ number_format($item->nominal, 0, ',', '.') }}
                                                        </span>
                                                    </td>
                                                    <td class="text-center">
                                                        <form action="{{ $item->kategori == 'Pemasukan' ? route('uangmasuk.destroy', $item->id) : route('uangkeluar.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus data ini?')">
                                                            @csrf @method('DELETE')
                                                            <button type="submit" class="btn btn-icon btn-sm btn-outline-danger border-0">
                                                                <i class="bx bx-trash-alt"></i>
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                                @empty
                                                <tr>
                                                    <td colspan="6" class="text-center py-5">
                                                        <i class="bx bx-file-blank display-1 text-light mb-3"></i>
                                                        <p class="text-muted">Wah, belum ada transaksi nih, King!</p>
                                                    </td>
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
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>

    <script>
        function updateClock() {
            const now = new Date();
            const h = String(now.getHours()).padStart(2, '0');
            const m = String(now.getMinutes()).padStart(2, '0');
            const s = String(now.getSeconds()).padStart(2, '0');
            document.getElementById('clock').innerText = `${h}:${m}:${s}`;
            const options = { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' };
            document.getElementById('current-date').innerText = now.toLocaleDateString('id-ID', options);
        }
        setInterval(updateClock, 1000);
        updateClock();
    </script>
</body>
</html>