<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Profil sayfasını görüntüle (Web için).
     */
    public function index()
    {
        return view('profile');
    }

    /**
     * Kullanıcı avatarını güncelle.
     */
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

        if ($request->expectsJson()) {
            return response()->json(['status' => 'ok', 'avatar' => $avatarName], 200);
        }

        return redirect()->back()->with('success', 'Avatar başarıyla güncellendi.');
    }

    /**
     * Kullanıcı bilgilerini güncelle.
     */
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

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Profil başarıyla güncellendi.'], 200);
        }

        return redirect()->back()->with('success', 'Profil başarıyla güncellendi.');
    }

    /**
     * Kullanıcı şifresini güncelle.
     */
    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Mevcut şifre yanlış.'], 400);
            }
            return redirect()->back()->withErrors(['current_password' => 'Mevcut şifre yanlış.']);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Şifre başarıyla değiştirildi.'], 200);
        }

        return redirect()->back()->with('success', 'Şifre başarıyla değiştirildi.');
    }

    /**
     * Bildirim tercihlerini güncelle.
     */
    public function updateNotifications(Request $request)
    {
        $user = Auth::user();

        // Değişiklik: has() yerine input() kullanılarak değer doğru bir şekilde alınıyor
        $user->email_notifications = $request->input('email_notifications');
        $user->save();

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Bildirim tercihleri güncellendi.'], 200);
        }

        return redirect()->back()->with('success', 'Bildirim tercihleri güncellendi.');
    }
}
