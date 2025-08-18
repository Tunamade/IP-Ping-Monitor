<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function index()
    {
        return view('profile');
    }

    public function updateAvatar(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'avatar' => 'required|file|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($user->avatar && Storage::disk('public')->exists('avatars/' . $user->avatar)) {
            Storage::disk('public')->delete('avatars/' . $user->avatar);
        }

        $avatarName = time() . '.png';
        Storage::disk('public')->put('avatars/' . $avatarName, file_get_contents($request->file('avatar')));

        $user->avatar = $avatarName;
        $user->save();

        return response()->json(['status' => 'ok', 'avatar' => $avatarName]);
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'about' => 'nullable|string|max:1000',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'about' => $request->about,
        ]);

        return redirect()->back()->with('success', 'Profil başarıyla güncellendi.');
    }

    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()->withErrors(['current_password' => 'Mevcut şifre yanlış.']);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->back()->with('success', 'Şifre başarıyla değiştirildi.');
    }

    // 🔹 Bildirim tercihlerini güncelle
    public function updateNotifications(Request $request)
    {
        $user = Auth::user();
        $user->email_notifications = $request->has('email_notifications');
        $user->save();

        return redirect()->back()->with('success', 'Bildirim tercihleri güncellendi.');
    }
}
