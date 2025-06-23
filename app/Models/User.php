<?php

namespace App\Models;

use Filament\Http\Responses\Auth\Contracts\PasswordResetResponse;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Filament\Panel;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class User extends Authenticatable implements FilamentUser, MustVerifyEmail, CanResetPassword
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'address',
        'city',
        'is_admin',
    ];

    protected $primaryKey = 'id_user';

    protected $hidden = [
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function canAccessPanel(Panel $panel): bool
    {
        // dd($panel, $panel->getAuthGuard(), Auth::user(), Auth::guard($panel->getAuthGuard())->user(), $this);
        // $user = $panel->getAuthGuard()
        //     ? Auth::guard($panel->getAuthGuard())->user()
        //     : Auth::user();
        $user = Auth::guard($panel->getAuthGuard())->user() ?: $this;

        if ($panel->getId() === 'admin' && $user->is_admin && $user->email_verified_at) {
            return true;
        }

        if ($panel->getId() === 'customer' && ! $user->is_admin) {
            return true;
        }

        return false;
    }

    public function rentals(): HasMany
    {
        return $this->hasMany(Rental::class, 'id_user');
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new \App\Notifications\FilamentResetPassword($token));
    }
}
