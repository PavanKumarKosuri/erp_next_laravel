<?php

namespace Modules\Finance\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\User;

class Journal extends Model
{
    protected $fillable = [
        'journal_number',
        'date',
        'reference',
        'description',
        'status',
        'user_id',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    // Status constants
    const STATUS_DRAFT = 'draft';
    const STATUS_POSTED = 'posted';
    const STATUS_REVERSED = 'reversed';

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function entries(): HasMany
    {
        return $this->hasMany(JournalEntry::class);
    }

    public function journalEntries(): HasMany
    {
        return $this->hasMany(JournalEntry::class);
    }

    // Boot method for auto-numbering
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($journal) {
            if (empty($journal->journal_number)) {
                $journal->journal_number = self::generateJournalNumber();
            }
        });
    }

    private static function generateJournalNumber(): string
    {
        $date = now()->format('Ymd');
        $count = self::whereDate('created_at', today())->count() + 1;
        return 'JRN-' . $date . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);
    }

    // Helper methods
    public static function getStatuses(): array
    {
        return [
            self::STATUS_DRAFT => 'Draft',
            self::STATUS_POSTED => 'Posted',
            self::STATUS_REVERSED => 'Reversed',
        ];
    }

    public function getTotalDebitAttribute(): float
    {
        return $this->entries()->sum('debit');
    }

    public function getTotalCreditAttribute(): float
    {
        return $this->entries()->sum('credit');
    }

    public function isBalanced(): bool
    {
        return round($this->total_debit, 2) === round($this->total_credit, 2);
    }
}
