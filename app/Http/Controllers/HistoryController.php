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

        // Mengambil log dengan relasi user untuk efisiensi (Eager Loading)
        $logs = ActivityLog::with('user')
            // Filter: Admin melihat semua, User biasa hanya melihat miliknya sendiri
            ->when($user->role !== 'admin', function($query) use ($user) {
                return $query->where('user_id', $user->id);
            })
            // Mengurutkan berdasarkan data terbaru (created_at)
            ->latest()
            // Sesuaikan pagination dengan kebutuhan view (misal: 10 atau 15)
            ->paginate(10);

        return view('history.index', compact('logs'));
    }

    /**
     * Menghapus seluruh riwayat (Khusus Admin)
     */
    public function clear()
    {
        // Proteksi tambahan untuk memastikan hanya admin yang bisa eksekusi
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Akses ditolak. Hanya Admin yang dapat membersihkan riwayat.');
        }

        // Menghapus semua data di tabel activity_logs
        ActivityLog::truncate();

        return redirect()->route('history.index')->with('success', 'Seluruh riwayat aktivitas telah berhasil dibersihkan!');
    }
}