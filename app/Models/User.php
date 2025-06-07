<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Filament\Panel;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;

class User extends Authenticatable implements FilamentUser, MustVerifyEmail
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
        $user = Auth::user();

        if ($panel->getId() === 'admin' && $user->is_admin && $user->email_verified_at) {
            return true;
        } elseif ($panel->getId() == 'customer' && !$user->is_admin) {
            return true;
        } else {
            return false;
        }
    }

    public function isAdmin(): bool
    {
        return Auth::user()->is_admin;
    }
    public function isCustomer(): bool
    {
        return !Auth::user()->is_admin;
    }
    public function messages(): HasMany
    {
        return $this->hasMany(Message::class, 'id_user');
    }
}
