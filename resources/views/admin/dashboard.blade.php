@extends('layouts.app') {{-- Pastikan ini mengarah ke file layout utama Anda --}}

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        
        <div class="col-lg-12 mb-4">
            <div class="card bg-label-primary border-0 shadow-sm">
                <div class="d-flex align-items-end row">
                    <div class="col-sm-7">
                        <div class="card-body">
                            <h5 class="card-title text-primary">
                                Selamat Datang, {{ Auth::user()->role == 'admin' ? 'Admin' : '' }} {{ Auth::user()->name }}! 🛡️
                            </h5>
                            <p class="mb-4">
                                @if(Auth::user()->role == 'admin')
                                    Sistem berjalan normal. Anda memantau total <strong>{{ $totalUser }}</strong> pengguna aktif dalam database global.
                                @else
                                    Pantau kondisi keuanganmu hari ini. Pastikan setiap pemasukan dan pengeluaran tercatat dengan rapi.
                                @endif
                            </p>
                            <a href="{{ route('history.index') }}" class="btn btn-sm btn-outline-primary">Lihat Aktivitas</a>
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
                    <span class="d-block mb-1 text-dark fw-semibold">Total Saldo {{ Auth::user()->role == 'admin' ? 'Global' : 'Saya' }}</span>
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
                    <span class="d-block mb-1 text-dark fw-semibold">Total Pemasukan</span>
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
                    <span class="d-block mb-1 text-dark fw-semibold">Total Pengeluaran</span>
                    <h4 class="card-title text-danger fw-bold">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</h4>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title m-0 fw-bold text-primary">
                        10 Transaksi Terakhir {{ Auth::user()->role == 'admin' ? '(Seluruh User)' : '' }}
                    </h5>
                    <div class="dropdown">
                        <button class="btn p-0" type="button" id="cardOpt3" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="bx bx-dots-vertical-rounded"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt3">
                            <a class="dropdown-item" href="{{ route('uangmasuk.index') }}">Data Pemasukan</a>
                            <a class="dropdown-item" href="{{ route('uangkeluar.index') }}">Data Pengeluaran</a>
                        </div>
                    </div>
                </div>
                <div class="table-responsive text-nowrap">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>User</th>
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
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-xs me-2">
                                            <span class="avatar-initial rounded-circle bg-label-primary"><i class="bx bx-user"></i></span>
                                        </div>
                                        <div class="d-flex flex-column">
                                            {{-- NAMA USER DINAMIS DARI RELASI --}}
                                            <span class="fw-bold text-dark">{{ $item->user->name ?? 'User Tidak Dikenal' }}</span>
                                            <small class="text-muted" style="font-size: 0.7rem;">ID Transaksi: #{{ $item->id }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span class="text-dark">
                                            {{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d M Y') }}
                                        </span>
                                        <small class="text-muted">
                                            <i class="bx bx-time-five" style="font-size: 10px"></i> 
                                            {{ $item->created_at ? $item->created_at->format('H:i') : '00:00' }} WIB
                                        </small>
                                    </div>
                                </td>
                                <td>
                                    <span class="fw-medium text-capitalize">{{ \Illuminate\Support\Str::limit($item->keterangan, 25) }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-label-{{ $item->kategori == 'Pemasukan' ? 'success' : 'danger' }} rounded-pill">
                                        <i class="bx {{ $item->kategori == 'Pemasukan' ? 'bx-trending-up' : 'bx-trending-down' }} mb-1"></i>
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
                                            @php 
                                                $routePrefix = ($item->kategori == 'Pemasukan' ? 'uangmasuk' : 'uangkeluar');
                                            @endphp
                                            <a class="dropdown-item" href="{{ route($routePrefix . '.edit', $item->id) }}">
                                                <i class="bx bx-edit-alt me-1"></i> Edit
                                            </a>
                                            <form action="{{ route($routePrefix . '.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus transaksi ini?')">
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
                                <td colspan="6" class="text-center p-5 text-muted">
                                    <i class="bx bx-folder-open fs-1 d-block mb-2"></i>
                                    Belum ada data transaksi yang ditemukan.
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
@endsection