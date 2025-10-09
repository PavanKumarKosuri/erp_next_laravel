<?php

namespace Modules\Production\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Inventory\Models\Product;

class BillOfMaterial extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'bom_number',
        'product_id',
        'name',
        'version',
        'quantity',
        'unit_cost',
        'total_cost',
        'description',
        'is_active',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'unit_cost' => 'decimal:2',
        'total_cost' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function components(): HasMany
    {
        return $this->hasMany(BomComponent::class, 'bom_id');
    }

    public function workOrders(): HasMany
    {
        return $this->hasMany(WorkOrder::class, 'bom_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Accessors
    public function getTotalComponentCostAttribute()
    {
        return $this->components->sum(function ($component) {
            return $component->quantity * $component->unit_cost;
        });
    }

    // Boot
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($bom) {
            if (empty($bom->bom_number)) {
                $bom->bom_number = 'BOM-' . str_pad(
                    (BillOfMaterial::withTrashed()->max('id') ?? 0) + 1,
                    6,
                    '0',
                    STR_PAD_LEFT
                );
            }
        });

        static::saving(function ($bom) {
            // Calculate total cost from components
            if ($bom->exists) {
                $bom->total_cost = $bom->total_component_cost;
                $bom->unit_cost = $bom->quantity > 0 ? $bom->total_cost / $bom->quantity : 0;
            }
        });
    }
}
