<?php

namespace App\Models\Bom;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Process extends Model
{
    use HasFactory;

    protected $table = 'bom_processes';

    protected $fillable = [
        'pcf_id', 'name', 'inputs', 'outputs', 'suppliers', 'customers', 'resources', 'controls', 'description',

    ];

    protected $casts = [

    ];

    public function pcf(): BelongsTo
    {
        return $this->belongsTo(pcf::class);
    }
}
