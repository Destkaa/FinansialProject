<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('profile.index', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->hasFile('avatar')) {
            // Pastikan folder avatars ada
            if (!Storage::disk('public')->exists('avatars')) {
                Storage::disk('public')->makeDirectory('avatars');
            }

            // Hapus foto lama jika ada
            if ($user->avatar && Storage::disk('public')->exists('avatars/' . $user->avatar)) {
                Storage::disk('public')->delete('avatars/' . $user->avatar);
            }
            
            // Simpan foto baru ke storage/app/public/avatars
            $fileName = time() . '_' . $user->id . '.' . $request->avatar->extension();
            $request->avatar->storeAs('avatars', $fileName, 'public');
            
            $user->avatar = $fileName;
        }

        $user->save();
        return back()->with('success', 'Profil berhasil diperbarui!');
    }

    public function updatePassword(Request $request)
    {
        $request->validate(['password' => 'required|min:8|confirmed']);
        Auth::user()->update(['password' => Hash::make($request->password)]);
        return back()->with('success', 'Password berhasil diperbarui!');
    }

    public function destroyAvatar()
    {
        $user = Auth::user();
        if ($user->avatar && Storage::disk('public')->exists('avatars/' . $user->avatar)) {
            Storage::disk('public')->delete('avatars/' . $user->avatar);
            $user->avatar = null;
            $user->save();
        }
        return back()->with('success', 'Foto profil dihapus.');
    }
}