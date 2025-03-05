<?php

namespace App\Models\Org;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class JobPosition extends Model
{
    use HasFactory;

    protected $table = 'org_job_positions';

    protected $fillable = ['title', 'unique_id', 'created_at'];

    public static function boot()
    {
        parent::boot();

        // Hook into the creating event
        static::creating(function ($model) {
            $model->unique_id = $model->generateUniqueId();
        });
    }

    public function generateUniqueId()
    {
        $timestamp = \Carbon\Carbon::parse($this->created_at)->timestamp;

        return $this->title.$timestamp;
    }

    public static function create(array $attributes = [])
    {
        if (empty($attributes['reports_to'])) {
            $attributes['reports_to'] = null;
        }

        return static::query()->create($attributes);
    }

    public function jobRoles(): BelongsToMany
    {
        return $this->belongsToMany(JobRole::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class);
    }
}
