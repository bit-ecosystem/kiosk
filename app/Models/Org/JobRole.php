<?php

namespace App\Models\Org;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Permission\Traits\HasRoles;

class JobRole extends Model
{
    use HasFactory, HasRoles;

    protected $table = 'org_job_roles';

    protected $fillable = [
        'name', 'description',
    ];

    protected $casts = [

    ];

    public function jobPositions(): HasMany
    {
        return $this->hasMany(JobPosition::class);
    }
}
