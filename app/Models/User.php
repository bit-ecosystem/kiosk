<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasRoles;

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;

    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
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

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            if (is_null($user->user_type_id)) {
                $user->user_type_id = UserType::first()->id;
            }

            // Set default password if not provided
            if (is_null($user->password)) {
                $user->password = Hash::make('P@ssword');
            }
        });
        static::created(function ($user) {
            // Find the role based on user_type_id
            $roleNamePrefix = 'ut' . $user->user_type_id . '-';
            $role = Role::where('name', 'like', $roleNamePrefix . '%')->first();

            if ($role) {
                // Assign the role to the user
                $user->assignRole($role->name);
            }
        });
    }

    public function userType()
    {
        return $this->belongsTo(UserType::class);
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return in_array($this->userType->type_name, ['Employee', 'Vendor Contractor', 'Admin']);
    }

    public function canAccessTenant(Model $tenant): bool
    {
        return true;
    }

    public function groups()
    {
        return $this->belongsToMany(Group::class);
    }
    public function staff(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }
}
