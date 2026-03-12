@extends('layouts.app')

@section('content')
<style>
    /* 1. Background Animasi Gradasi (Senada dengan Login/Regis) */
    body {
        background: linear-gradient(-45deg, #696cff, #8e91ff, #f5f5f9, #e7e7ff);
        background-size: 400% 400%;
        animation: gradientBG 15s ease infinite;
        min-height: 100vh;
        display: flex;
        align-items: center;
    }

    @keyframes gradientBG {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }

    /* 2. Glassmorphism Card */
    .card-verify {
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(15px);
        border: 1px solid rgba(255, 255, 255, 0.4);
        border-radius: 1.5rem !important;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1) !important;
        padding: 2rem;
        text-align: center;
        animation: slideIn 0.6s ease-out;
    }

    @keyframes slideIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* 3. Icon Animation */
    .verify-icon {
        font-size: 4rem;
        color: #696cff;
        margin-bottom: 1.5rem;
        display: inline-block;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.1); }
        100% { transform: scale(1); }
    }

    .btn-resend {
        background-color: #696cff;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-resend:hover {
        background-color: #5f61e6;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(105, 108, 255, 0.4);
        color: white;
    }

    .alert-success {
        border-radius: 10px;
        border: none;
        background-color: rgba(113, 221, 55, 0.2);
        color: #71dd37;
        font-weight: 500;
    }
</style>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card card-verify">
                <div class="card-body">
                    <div class="verify-icon">
                        <i class="bx bx-envelope-open"></i>
                    </div>

                    <h3 class="mb-3 fw-bold">Verifikasi Email Anda</h3>
                    
                    @if (session('resent'))
                        <div class="alert alert-success mb-4" role="alert">
                            <i class="bx bx-check-circle me-1"></i> 
                            Link verifikasi baru telah dikirim ke email Anda!
                        </div>
                    @endif

                    <p class="text-muted mb-4">
                        Sebelum melanjutkan, silakan periksa email Anda untuk menemukan link verifikasi. 
                        Jika Anda tidak menerima email dari kami...
                    </p>

                    <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                        @csrf
                        <button type="submit" class="btn btn-resend w-100 mb-3">
                            Kirim Ulang Email Verifikasi
                        </button>
                    </form>

                    <a href="/" class="text-decoration-none small text-secondary">
                        <i class="bx bx-left-arrow-alt"></i> Kembali ke Beranda
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection