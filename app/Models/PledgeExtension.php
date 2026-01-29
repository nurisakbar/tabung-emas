<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class PledgeExtension extends Model
{
    protected $fillable = [
        'pledge_id',
        'extension_duration',
        'extension_fee',
        'new_end_date',
        'status',
        'approved_by',
        'approved_at',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'extension_fee' => 'decimal:2',
            'new_end_date' => 'date',
            'approved_at' => 'datetime',
        ];
    }

    /**
     * Get the pledge for this extension
     */
    public function pledge(): BelongsTo
    {
        return $this->belongsTo(GoldPledge::class, 'pledge_id');
    }

    /**
     * Get the admin who approved this extension
     */
    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Approve extension
     */
    public function approve($approvedBy): void
    {
        $this->status = 'approved';
        $this->approved_by = $approvedBy;
        $this->approved_at = Carbon::now();
        $this->save();
    }
}
