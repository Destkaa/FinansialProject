{{-- resources/views/profile/partials/update-avatar-form.blade.php --}}

<p class="text-muted small">
    Upload foto profil kamu. Format yang didukung: JPG, PNG, WebP. Maksimal 2MB.
</p>

<form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data">
    @csrf
    @method('patch')

    <div class="d-flex align-items-center gap-4">
        {{-- Avatar Preview --}}
        <div class="position-relative">
            {{-- 
                PERBAIKAN UTAMA: Menggunakan properti avatar_url dari Model User. 
                Ini akan otomatis menangani fallback ke Gravatar jika file di storage hilang.
            --}}
            <img id="avatar-preview" 
                 class="rounded-circle object-fit-cover border" 
                 style="width: 100px; height: 100px;"
                 src="{{ $user->avatar_url }}" 
                 alt="{{ $user->name }}">

            {{-- Tombol Hapus Foto (Hanya muncul jika user punya foto di database) --}}
            @if($user->avatar && !str_starts_with($user->avatar, 'http'))
            <button type="button"
                onclick="if(confirm('Hapus foto profil?')) document.getElementById('delete-avatar-form').submit()"
                class="btn btn-danger btn-sm rounded-circle position-absolute top-0 start-100 translate-middle p-1"
                style="width: 24px; height: 24px; line-height: 1;" 
                title="Hapus foto">
                &times;
            </button>
            @endif
        </div>

        {{-- Upload Input --}}
        <div class="flex-grow-1">
            <input type="file" 
                   name="avatar" 
                   id="avatar" 
                   accept="image/*" 
                   onchange="previewAvatar(event)"
                   class="form-control @error('avatar') is-invalid @enderror">
            
            @error('avatar')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="mt-3">
        <button type="submit" class="btn btn-primary">Simpan Foto</button>
    </div>
</form>

{{-- Form Tersembunyi untuk Proses Hapus Avatar --}}
<form id="delete-avatar-form" action="{{ route('profile.avatar.destroy') }}" method="POST" class="d-none">
    @csrf
    @method('DELETE')
</form>

<script>
    /**
     * Fungsi untuk menampilkan preview foto secara instan 
     * sebelum file benar-benar di-upload ke server.
     */
    function previewAvatar(event) {
        const file = event.target.files[0];
        if (file) {
            // Validasi ukuran file di sisi client (Opsional, max 2MB)
            if (file.size > 2 * 1024 * 1024) {
                alert('Ukuran file terlalu besar! Maksimal 2MB.');
                event.target.value = '';
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('avatar-preview').src = e.target.result;
            }
            reader.readAsDataURL(file);
        }
    }
</script>