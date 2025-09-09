<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens; // Sanctum için eklendi
use App\Models\Ping;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable; // HasApiTokens eklendi

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',  // Profil resmi
        'about',   // Hakkımda kısmı
        'email_notifications', // Mail bildirim tercihi
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'email_notifications' => 'boolean',
    ];

    /**
     * Kullanıcının ping kayıtları
     */
    public function pings()
    {
        return $this->hasMany(Ping::class);
    }
}
