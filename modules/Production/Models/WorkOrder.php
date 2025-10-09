<?php

namespace Modules\Production\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\User;
use Modules\Inventory\Models\Product;
use Modules\Inventory\Models\Warehouse;

class WorkOrder extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'wo_number',
        'bom_id',
        'product_id',
        'warehouse_id',
        'planned_quantity',
        'produced_quantity',
        'rejected_quantity',
        'start_date',
        'end_date',
        'expected_completion_date',
        'actual_completion_date',
        'status',
        'priority',
        'notes',
        'user_id',
    ];

    protected $casts = [
        'planned_quantity' => 'decimal:2',
        'produced_quantity' => 'decimal:2',
        'rejected_quantity' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
        'expected_completion_date' => 'date',
        'actual_completion_date' => 'date',
    ];

    // Relationships
    public function bom(): BelongsTo
    {
        return $this->belongsTo(BillOfMaterial::class, 'bom_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function outputs(): HasMany
    {
        return $this->hasMany(ProductionOutput::class);
    }

    public function qualityChecks(): HasMany
    {
        return $this->hasMany(QualityCheck::class);
    }

    // Scopes
    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    // Accessors
    public function getRemainingQuantityAttribute()
    {
        return $this->planned_quantity - $this->produced_quantity;
    }

    public function getCompletionPercentageAttribute()
    {
        if ($this->planned_quantity == 0) {
            return 0;
        }
        return ($this->produced_quantity / $this->planned_quantity) * 100;
    }

    public function getIsCompletedAttribute()
    {
        return $this->produced_quantity >= $this->planned_quantity;
    }

    // Boot
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($wo) {
            if (empty($wo->wo_number)) {
                $wo->wo_number = 'WO-' . date('Ymd') . '-' . str_pad(
                    WorkOrder::whereDate('created_at', today())->count() + 1,
                    4,
                    '0',
                    STR_PAD_LEFT
                );
            }
        });
    }
}
