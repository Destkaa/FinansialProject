<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HistoryController extends Controller
{
    /**
     * Menampilkan riwayat aktivitas pengguna.
     */
    public function index()
    {
        $user = Auth::user();

        // Mengambil log dengan relasi user (Eager Loading)
        $logs = ActivityLog::with('user')
            // Admin melihat semua, User biasa hanya melihat miliknya sendiri
            ->when($user->role !== 'admin', function($query) use ($user) {
                return $query->where('user_id', $user->id);
            })
            ->latest()
            ->paginate(10); // Menggunakan pagination sesuai permintaan view

        return view('history.index', compact('logs'));
    }

    /**
     * Menghapus seluruh riwayat (Khusus Admin)
     */
    public function clear()
    {
        // Proteksi tambahan (Sudah tercover middleware, tapi ini backup yang bagus)
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Akses ditolak. Hanya Admin yang dapat membersihkan riwayat.');
        }

        // OPSIONAL: Catat siapa admin yang menghapus log sebelum semuanya hilang
        // Jika kamu ingin riwayat "Pembersihan" tetap ada satu baris, gunakan delete() bukan truncate()
        // ActivityLog::whereNotNull('id')->delete(); 
        
        // Menggunakan truncate untuk reset total tabel (ID kembali ke 1)
        ActivityLog::truncate();

        // Tambahkan satu log baru bahwa riwayat telah dibersihkan (Opsional)
        ActivityLog::create([
            'user_id' => Auth::id(),
            'activity' => 'HAPUS',
            'description' => 'Membersihkan seluruh riwayat aktivitas sistem.',
            'ip_address' => request()->ip(),
        ]);

        return redirect()->route('history.index')->with('success', 'Seluruh riwayat aktivitas telah berhasil dibersihkan!');
    }
}