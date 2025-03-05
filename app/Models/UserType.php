<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Models\Role;
class UserType extends Model
{
    use HasFactory, HasRoles;

    protected $fillable = [
        'type_name', 'description', 'tag', 'home',
    ];

    protected $casts = [

    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
    protected static function boot()
    {
        parent::boot();

        static::created(function ($userType) {
            // Ensure type_name and tag are lowercase, spaces removed, and null values replaced with 'any'
            $typeName = strtolower(str_replace(' ', '', $userType->type_name ?? 'any'));
            $tag = strtolower(str_replace(' ', '', $userType->tag ?? 'any'));

            // Create a role with the name 'view-{type_name}-{tag}'
            Role::create(['name' => 'ut'.$userType->id.'-' . $typeName . '-' . $tag]);
        });
    }
}
