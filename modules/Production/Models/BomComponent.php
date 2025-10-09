<?php

namespace Modules\Production\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Inventory\Models\Product;

class BomComponent extends Model
{
    protected $fillable = [
        'bom_id',
        'product_id',
        'quantity',
        'unit_cost',
        'total_cost',
        'scrap_percentage',
        'notes',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'unit_cost' => 'decimal:2',
        'total_cost' => 'decimal:2',
        'scrap_percentage' => 'decimal:2',
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

    // Accessors
    public function getAdjustedQuantityAttribute()
    {
        $scrap = $this->scrap_percentage / 100;
        return $this->quantity * (1 + $scrap);
    }

    // Boot
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($component) {
            // Calculate total cost
            $component->total_cost = $component->quantity * $component->unit_cost;
        });
    }
}
