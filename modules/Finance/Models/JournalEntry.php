<?php

namespace Modules\Finance\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JournalEntry extends Model
{
    protected $fillable = [
        'journal_id',
        'account_id',
        'debit',
        'credit',
        'description',
    ];

    protected $casts = [
        'debit' => 'decimal:2',
        'credit' => 'decimal:2',
    ];

    // Relationships
    public function journal(): BelongsTo
    {
        return $this->belongsTo(Journal::class);
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    // Boot method for auto-calculation
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($entry) {
            // Ensure either debit or credit is set, not both
            if ($entry->debit > 0) {
                $entry->credit = 0;
            } elseif ($entry->credit > 0) {
                $entry->debit = 0;
            }
        });

        static::updating(function ($entry) {
            // Ensure either debit or credit is set, not both
            if ($entry->debit > 0) {
                $entry->credit = 0;
            } elseif ($entry->credit > 0) {
                $entry->debit = 0;
            }
        });
    }

    // Helper methods
    public function getAmountAttribute(): float
    {
        return $this->debit > 0 ? $this->debit : $this->credit;
    }

    public function getTypeAttribute(): string
    {
        return $this->debit > 0 ? 'Debit' : 'Credit';
    }
}
