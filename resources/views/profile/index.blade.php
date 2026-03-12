@extends('layouts.app')

@section('content')
<style>
    /* Custom Styles untuk Profile Page */
    .profile-card {
        border: none;
        border-radius: 12px;
        transition: all 0.3s ease;
        background: #fff;
    }

    .profile-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(105, 108, 255, 0.1) !important;
    }

    .avatar-wrapper {
        width: 130px;
        height: 130px;
        margin: 0 auto 1.5rem;
        position: relative;
        padding: 5px;
        border: 2px dashed #696cff; /* Border putus-putus ungu */
        border-radius: 50%;
    }

    .avatar-img {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid #fff;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }

    .initial-avatar {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 3.5rem;
        font-weight: 700;
        color: #fff;
        background: linear-gradient(135deg, #696cff 0%, #303282 100%);
        border: 4px solid #fff;
    }

    .btn-update-photo {
        background-color: #696cff;
        border-color: #696cff;
        color: #fff;
        transition: all 0.2s;
    }

    .btn-update-photo:hover {
        background-color: #5f61e6;
        box-shadow: 0 4px 8px rgba(105, 108, 255, 0.4);
    }

    .form-label-custom {
        text-transform: uppercase;
        font-size: 0.75rem;
        font-weight: 700;
        color: #a1acb8;
        letter-spacing: 0.5px;
        margin-bottom: 0.5rem;
    }

    .form-control:focus {
        border-color: #696cff;
        box-shadow: 0 0 0 0.2rem rgba(105, 108, 255, 0.1);
    }

    .badge-role {
        background-color: rgba(105, 108, 255, 0.1);
        color: #696cff;
        font-weight: 600;
        padding: 0.5rem 1.2rem;
        border-radius: 8px;
        font-size: 0.8rem;
    }
</style>

<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-xl-4 col-lg-5 col-md-5 mb-4">
            <div class="card profile-card shadow-sm">
                <div class="card-body text-center pt-5">
                    <div class="avatar-wrapper">
                        @if($user->avatar)
                            <img src="{{ asset('storage/avatars/' . $user->avatar) }}?t={{ time() }}" 
                                 id="avatar-preview" class="avatar-img">
                        @else
                            <div id="initial-preview" class="initial-avatar">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <img id="avatar-preview" class="avatar-img" style="display: none;">
                        @endif
                    </div>

                    <h4 class="mb-1 fw-bold">{{ $user->name }}</h4>
                    <p class="text-muted mb-3">{{ $user->email }}</p>
                    <div class="mb-4">
                        <span class="badge-role">{{ strtoupper($user->role) }}</span>
                    </div>

                    <div class="d-flex justify-content-center gap-2 border-top pt-4">
                         <a href="{{ route('home') }}" class="btn btn-outline-secondary w-100">
                            <i class="bx bx-left-arrow-alt me-1"></i> Dashboard
                         </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-8 col-lg-7 col-md-7">
            @if(session('success'))
                <div class="alert alert-primary d-flex align-items-center" role="alert">
                    <i class="bx bx-check-circle me-2"></i>
                    <div>{{ session('success') }}</div>
                </div>
            @endif

            <div class="card profile-card shadow-sm mb-4">
                <div class="card-header d-flex justify-content-between align-items-center border-bottom bg-transparent py-3">
                    <h5 class="m-0 fw-bold"><i class="bx bx-image-alt me-2"></i>Foto Profil</h5>
                </div>
                <div class="card-body pt-4">
                    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="name" value="{{ $user->name }}">
                        <input type="hidden" name="email" value="{{ $user->email }}">

                        <div class="row align-items-center">
                            <div class="col-sm-8 mb-3 mb-sm-0">
                                <input type="file" name="avatar" id="avatar-input" class="form-control" accept="image/*" required>
                                <div class="form-text mt-2 text-muted">Format: JPG, PNG, JPEG. Max 2MB.</div>
                            </div>
                            <div class="col-sm-4 text-sm-end">
                                <button type="submit" class="btn btn-update-photo px-4">Upload Baru</button>
                            </div>
                        </div>
                    </form>

                    @if($user->avatar)
                        <div class="mt-3">
                            <button type="button" 
                                    onclick="if(confirm('Hapus foto profil?')) document.getElementById('delete-avatar-form').submit();"
                                    class="btn btn-link text-danger p-0 fw-bold small text-decoration-none">
                                <i class="bx bx-trash me-1"></i> Hapus Foto Saat Ini
                            </button>
                        </div>
                    @endif
                </div>
            </div>

            <div class="card profile-card shadow-sm mb-4">
                <div class="card-header border-bottom bg-transparent py-3">
                    <h5 class="m-0 fw-bold"><i class="bx bx-user-circle me-2"></i>Informasi Akun</h5>
                </div>
                <div class="card-body pt-4">
                    <form action="{{ route('profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label class="form-label-custom">Nama Lengkap</label>
                                <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                            </div>
                            <div class="col-md-6 mb-4">
                                <label class="form-label-custom">Alamat Email</label>
                                <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                            </div>
                        </div>
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary px-4">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card profile-card shadow-sm border-label-danger">
                <div class="card-header border-bottom bg-transparent py-3">
                    <h5 class="m-0 fw-bold text-danger"><i class="bx bx-shield-quarter me-2"></i>Keamanan</h5>
                </div>
                <div class="card-body pt-4">
                    <form action="{{ route('profile.password') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label class="form-label-custom">Password Baru</label>
                                <input type="password" name="password" class="form-control" placeholder="••••••••">
                            </div>
                            <div class="col-md-6 mb-4">
                                <label class="form-label-custom">Konfirmasi Password</label>
                                <input type="password" name="password_confirmation" class="form-control" placeholder="••••••••">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-danger px-4">Ganti Password</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<form id="delete-avatar-form" action="{{ route('profile.avatar.destroy') }}" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<script>
    document.getElementById('avatar-input').onchange = evt => {
        const [file] = document.getElementById('avatar-input').files
        if (file) {
            const preview = document.getElementById('avatar-preview');
            const initial = document.getElementById('initial-preview');
            
            preview.src = URL.createObjectURL(file);
            preview.style.display = 'block';
            if(initial) initial.style.display = 'none';
        }
    }
</script>
@endsection