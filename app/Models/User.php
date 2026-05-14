<?php

namespace App\Models;

use App\Models\Concerns\BelongsToClinic;
use Database\Factories\UserFactory;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;

class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<UserFactory> */
    use BelongsToClinic, HasFactory, Notifiable, TwoFactorAuthenticatable;

    public const ROLE_SUPER_ADMIN = 'super_admin';

    public const ROLE_CLINIC_OWNER = 'clinic_owner';

    public const ROLE_CLINIC_ADMIN = 'clinic_admin';

    public const ROLE_DENTIST = 'dentist';

    public const ROLE_TREATMENT_COORDINATOR = 'treatment_coordinator';

    public const ROLE_VIEWER = 'viewer';

    protected $fillable = [
        'name', 'email', 'password', 'clinic_id', 'role',
        'is_active', 'last_login_at', 'locale', 'avatar_path',
        'signature_path', 'title', 'specialisation',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_secret',
        'two_factor_recovery_codes',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'last_login_at' => 'datetime',
        ];
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === self::ROLE_SUPER_ADMIN;
    }

    public function isClinicOwner(): bool
    {
        return $this->role === self::ROLE_CLINIC_OWNER;
    }

    public function isClinicAdmin(): bool
    {
        return in_array($this->role, [self::ROLE_CLINIC_OWNER, self::ROLE_CLINIC_ADMIN]);
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return match ($panel->getId()) {
            'super' => $this->isSuperAdmin(),
            'admin' => $this->isSuperAdmin() || $this->isClinicAdmin(),
            default => true,
        };
    }

    protected static function bootBelongsToClinic(): void {}
}
