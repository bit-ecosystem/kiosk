<?php

namespace App\Models\Org;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Permission\Traits\HasRoles;

class StaffRole extends Model
{
    use HasFactory, HasRoles;

    protected $table = 'org_staff_roles';

    protected $fillable = [
        'role_name', 'description',
    ];

    protected $casts = [
    ];

    public function staff(): BelongsToMany
    {
        return $this->belongsToMany(Staff::class, 'org_staff_staff_role', 'org_staff_role_id', 'org_staff_id');
    }
}
