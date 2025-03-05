<?php

namespace App\Models\Org;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Permission\Traits\HasRoles;

class Staff extends Model
{
    use HasFactory, HasRoles;

    /**
     * @var string
     */
    protected $table = 'org_staff';

    protected $fillable = [
        'staffid', 'join_date', 'end_date', 'date_of_birth',
    ];

    protected $casts = [

    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($staff) {
            $retirementAge = (int) env('MANDATORY_RETIREMENT_AGE', 65);
            $staff->end_date = Carbon::parse($staff->date_of_birth)->addYears($retirementAge)->format('d-M-y');
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function staffRoles(): BelongsToMany
    {
        return $this->belongsToMany(StaffRole::class, 'org_staff_staff_role', 'org_staff_id', 'org_staff_role_id');
    }
}
