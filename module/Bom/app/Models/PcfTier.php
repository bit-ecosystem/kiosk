<?php

namespace App\Models\Bom;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PcfTier extends Model
{
    use HasFactory;

    protected $table = 'bom_pcf_tier';

    protected $fillable = [
        'level', 'description',
    ];

    protected $casts = [

    ];

    public function pcf(): HasMany
    {
        return $this->hasMany(Pcf::class);
    }
}
