<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HistoryController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $logs = ActivityLog::with('user')
            // Jika bukan admin, hanya ambil log miliknya sendiri
            ->when($user->role !== 'admin', function($query) use ($user) {
                return $query->where('user_id', $user->id);
            })
            ->latest()
            ->paginate(10);

        return view('history.index', compact('logs'));
    }
}