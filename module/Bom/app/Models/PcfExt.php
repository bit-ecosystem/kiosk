<?php

namespace App\Models\Bom;

use App\Models\Org\JobPosition;
use App\Models\Org\JobRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PcfExt extends Model
{
    use HasFactory;

    protected $table = 'bom_pcf_ext';

    protected $fillable = [
        'pcf_id', 'responsible', 'accountable', 'shortcode', 'apqc',

    ];

    protected $casts = [

    ];

    public function pcf(): BelongsTo
    {
        return $this->belongsTo(pcf::class);
    }

    public function responsible(): BelongsTo
    {
        return $this->belongsTo(JobRole::class);
    }

    public function accountable(): BelongsTo
    {
        return $this->belongsTo(JobPosition::class);
    }
}
