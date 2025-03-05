<?php

namespace App\Models\Org;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Company extends Model
{
    use HasFactory;

    protected $table = 'org_companies';

    protected $fillable = [
        'name', 'description',
    ];

    protected $casts = [

    ];

    public function divisions(): HasMany
    {
        return $this->hasMany(Division::class);
    }
}
