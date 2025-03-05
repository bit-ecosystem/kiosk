<?php

namespace App\Models\Bom;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pcf extends Model
{
    use HasFactory;

    protected $table = 'bom_pcf';

    protected $fillable = [
        'hierarchy_code',
        'name',
        'definition',
        'tier_id',
    ];

    protected $casts = [

    ];

    public function tier(): BelongsTo
    {
        return $this->belongsTo(PcfTier::class);
    }
}
