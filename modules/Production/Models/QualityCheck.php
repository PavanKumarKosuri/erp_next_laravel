<?php

namespace Modules\Production\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

class QualityCheck extends Model
{
    protected $fillable = [
        'work_order_id',
        'check_number',
        'check_date',
        'inspector',
        'sample_size',
        'passed_count',
        'failed_count',
        'defect_types',
        'status',
        'notes',
        'user_id',
    ];

    protected $casts = [
        'check_date' => 'date',
        'sample_size' => 'integer',
        'passed_count' => 'integer',
        'failed_count' => 'integer',
        'defect_types' => 'array',
    ];

    // Relationships
    public function workOrder(): BelongsTo
    {
        return $this->belongsTo(WorkOrder::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Accessors
    public function getPassRateAttribute()
    {
        if ($this->sample_size == 0) {
            return 0;
        }
        return ($this->passed_count / $this->sample_size) * 100;
    }

    public function getFailRateAttribute()
    {
        if ($this->sample_size == 0) {
            return 0;
        }
        return ($this->failed_count / $this->sample_size) * 100;
    }

    // Boot
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($check) {
            if (empty($check->check_number)) {
                $check->check_number = 'QC-' . date('Ymd') . '-' . str_pad(
                    QualityCheck::whereDate('created_at', today())->count() + 1,
                    4,
                    '0',
                    STR_PAD_LEFT
                );
            }
        });
    }
}
