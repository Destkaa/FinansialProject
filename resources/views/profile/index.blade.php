@extends('layouts.app')

@section('content')
<div class="container py-5" style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
    <div class="row">
        <div class="col-lg-4 mb-4">
            <div class="card" style="border: none; border-radius: 15px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); overflow: hidden;">
                <div class="card-body text-center" style="padding: 2rem;">
                    <div style="width: 120px; height: 120px; margin: 0 auto 1.5rem; position: relative;">
                        @if($user->avatar)
                            {{-- Tambahkan ?t=time() untuk memaksa browser refresh gambar baru --}}
                            <img src="{{ asset('storage/avatars/' . $user->avatar) }}?t={{ time() }}" 
                                 id="avatar-preview" 
                                 style="width: 120px !important; height: 120px !important; border-radius: 50% !important; object-fit: cover !important; display: block; border: 4px solid #fff; box-shadow: 0 2px 8px rgba(0,0,0,0.15);">
                        @else
                            <div id="initial-preview" 
                                 style="width: 120px !important; height: 120px !important; border-radius: 50% !important; background-color: {{ $user->role == 'admin' ? '#dc3545' : '#007bff' }}; color: white; display: flex; align-items: center; justify-content: center; font-size: 3rem; font-weight: bold; border: 4px solid #fff; box-shadow: 0 2px 8px rgba(0,0,0,0.15);">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <img id="avatar-preview" style="width: 120px !important; height: 120px !important; border-radius: 50% !important; object-fit: cover !important; display: none; border: 4px solid #fff; box-shadow: 0 2px 8px rgba(0,0,0,0.15);">
                        @endif
                    </div>

                    <h5 style="font-weight: 700; margin-bottom: 0.25rem; color: #333;">{{ $user->name }}</h5>
                    <p style="color: #6c757d; font-size: 0.9rem; margin-bottom: 1rem;">{{ $user->email }}</p>
                    <span style="padding: 0.4rem 1rem; border-radius: 50px; background: #f8f9fa; color: #333; font-size: 0.75rem; font-weight: 600; border: 1px solid #dee2e6; display: inline-block; margin-bottom: 1.5rem;">
                        {{ strtoupper($user->role) }}
                    </span>

                    <hr style="opacity: 0.1;">

                    {{-- Fitur Kembali ke Dashboard --}}
                    <div class="d-grid mt-3">
                        <a href="{{ route('home') }}" class="btn btn-outline-secondary" style="border-radius: 10px; font-weight: 600; padding: 0.6rem;">
                             Kembali ke Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            @if(session('success'))
                <div class="alert alert-success" style="border: none; border-radius: 10px; background-color: #d4edda; color: #155724; padding: 1rem; margin-bottom: 1.5rem;">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Form Foto Profil --}}
            <div class="card" style="border: none; border-radius: 15px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); margin-bottom: 1.5rem;">
                <div class="card-header" style="background: white; border-bottom: 1px solid #f0f0f0; padding: 1.25rem 1.5rem;">
                    <h6 style="margin: 0; font-weight: 700; color: #333;">Foto Profil</h6>
                </div>
                <div class="card-body" style="padding: 1.5rem;">
                    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        {{-- Penting: Hidden input agar data lain tetap terkirim --}}
                        <input type="hidden" name="name" value="{{ $user->name }}">
                        <input type="hidden" name="email" value="{{ $user->email }}">

                        <div style="display: flex; align-items: center; gap: 15px; flex-wrap: wrap;">
                            <div style="flex-grow: 1;">
                                <input type="file" name="avatar" id="avatar-input" class="form-control" accept="image/*" required>
                                <small style="color: #6c757d; display: block; margin-top: 5px;">Maksimal 2MB (JPG, PNG, JPEG).</small>
                            </div>
                            <button type="submit" class="btn btn-primary" style="padding: 0.5rem 1.5rem; border-radius: 8px; font-weight: 600;">Update Foto</button>
                        </div>
                    </form>

                    @if($user->avatar)
                        <div style="margin-top: 1rem;">
                            <button type="button" 
                                    onclick="if(confirm('Hapus foto profil?')) document.getElementById('delete-avatar-form').submit();"
                                    style="background: none; border: none; color: #dc3545; font-size: 0.85rem; padding: 0; cursor: pointer; font-weight: 600;">
                                Hapus Foto Saat Ini
                            </button>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Form Informasi Akun --}}
            <div class="card" style="border: none; border-radius: 15px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); margin-bottom: 1.5rem;">
                <div class="card-header" style="background: white; border-bottom: 1px solid #f0f0f0; padding: 1.25rem 1.5rem;">
                    <h6 style="margin: 0; font-weight: 700; color: #333;">Informasi Akun</h6>
                </div>
                <div class="card-body" style="padding: 1.5rem;">
                    <form action="{{ route('profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label style="display: block; font-size: 0.8rem; font-weight: 700; color: #6c757d; margin-bottom: 5px;">NAMA LENGKAP</label>
                                <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label style="display: block; font-size: 0.8rem; font-weight: 700; color: #6c757d; margin-bottom: 5px;">EMAIL</label>
                                <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                            </div>
                        </div>
                        <div style="text-align: right;">
                            <button type="submit" class="btn btn-dark" style="padding: 0.6rem 2rem; border-radius: 8px; font-weight: 600;">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Form Keamanan --}}
            <div class="card" style="border: none; border-radius: 15px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
                <div class="card-header" style="background: white; border-bottom: 1px solid #f0f0f0; padding: 1.25rem 1.5rem;">
                    <h6 style="margin: 0; font-weight: 700; color: #333;">Keamanan</h6>
                </div>
                <div class="card-body" style="padding: 1.5rem;">
                    <form action="{{ route('profile.password') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label style="display: block; font-size: 0.8rem; font-weight: 700; color: #6c757d; margin-bottom: 5px;">PASSWORD BARU</label>
                                <input type="password" name="password" class="form-control" placeholder="Minimal 8 karakter">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label style="display: block; font-size: 0.8rem; font-weight: 700; color: #6c757d; margin-bottom: 5px;">KONFIRMASI PASSWORD</label>
                                <input type="password" name="password_confirmation" class="form-control" placeholder="Ulangi password">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-danger" style="padding: 0.5rem 1.5rem; border-radius: 8px; font-weight: 600;">Update Password</button>
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