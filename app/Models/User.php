<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    public const ROLE_ADMIN = 'admin';

    public const ROLE_DOCTOR = 'doctor';

    public const ROLE_HR = 'hr';

    public const ROLE_ACCOUNTANT = 'accountant';

    public const ROLE_RECEPTIONIST = 'receptionist';

    public const STATUS_ACTIVE = 'active';

    public const STATUS_INACTIVE = 'inactive';

    public const ROLES = [
        self::ROLE_ADMIN,
        self::ROLE_DOCTOR,
        self::ROLE_HR,
        self::ROLE_ACCOUNTANT,
        self::ROLE_RECEPTIONIST,
    ];

    public const STATUSES = [
        self::STATUS_ACTIVE,
        self::STATUS_INACTIVE,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'status',
        'doctor_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Associated Doctor profile (if user is a doctor).
     */
    public function doctor(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Doctor::class, 'doctor_id');
    }

    /**
     * Role checking helpers.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isDoctor(): bool
    {
        return $this->role === 'doctor';
    }

    public function isHr(): bool
    {
        return $this->role === 'hr';
    }

    public function isAccountant(): bool
    {
        return $this->role === 'accountant';
    }

    public function isReceptionist(): bool
    {
        return $this->role === 'receptionist';
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Check if user has any of the given roles.
     *
     * @param  string|array<int, string>  $roles
     */
    public function hasRole(string|array $roles): bool
    {
        if ($this->isAdmin()) {
            return true;
        }

        $roles = is_array($roles) ? $roles : explode(',', (string) $roles);
        $roles = array_map('trim', $roles);

        return in_array($this->role, $roles, true);
    }

    /**
     * Module permission helpers.
     */
    public function canManageDoctors(): bool
    {
        return in_array($this->role, ['admin', 'hr'], true);
    }

    public function canManageStaff(): bool
    {
        return in_array($this->role, ['admin', 'hr'], true);
    }

    public function canAccessPayments(): bool
    {
        return in_array($this->role, ['admin', 'accountant'], true);
    }

    public function canAccessExpenses(): bool
    {
        return in_array($this->role, ['admin', 'accountant'], true);
    }

    public function canAccessAppointments(): bool
    {
        return in_array($this->role, ['admin', 'doctor', 'hr', 'receptionist'], true);
    }

    public function canAccessPatients(): bool
    {
        return in_array($this->role, ['admin', 'doctor', 'hr', 'receptionist', 'accountant'], true);
    }

    public function canManageUsers(): bool
    {
        return $this->role === 'admin';
    }
}
