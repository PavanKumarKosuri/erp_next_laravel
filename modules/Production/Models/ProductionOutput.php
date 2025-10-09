<?php

namespace Modules\Production\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

class ProductionOutput extends Model
{
    protected $fillable = [
        'work_order_id',
        'output_number',
        'production_date',
        'quantity_produced',
        'quantity_rejected',
        'batch_number',
        'notes',
        'user_id',
    ];

    protected $casts = [
        'production_date' => 'date',
        'quantity_produced' => 'decimal:2',
        'quantity_rejected' => 'decimal:2',
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
    public function getQuantityAcceptedAttribute()
    {
        return $this->quantity_produced - $this->quantity_rejected;
    }

    public function getYieldPercentageAttribute()
    {
        if ($this->quantity_produced == 0) {
            return 0;
        }
        return ($this->quantity_accepted / $this->quantity_produced) * 100;
    }

    // Boot
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($output) {
            if (empty($output->output_number)) {
                $output->output_number = 'OUT-' . date('Ymd') . '-' . str_pad(
                    ProductionOutput::whereDate('created_at', today())->count() + 1,
                    4,
                    '0',
                    STR_PAD_LEFT
                );
            }
        });

        static::created(function ($output) {
            // Update work order produced quantity
            $workOrder = $output->workOrder;
            if ($workOrder) {
                $workOrder->increment('produced_quantity', $output->quantity_accepted);
                $workOrder->increment('rejected_quantity', $output->quantity_rejected);

                // Auto-complete if all quantity produced
                if ($workOrder->is_completed && $workOrder->status !== 'completed') {
                    $workOrder->update([
                        'status' => 'completed',
                        'actual_completion_date' => now(),
                    ]);
                }
            }
        });
    }
}
