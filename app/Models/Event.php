<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'title',
        'description',
        'image',
        'event_date',
        'event_time',
        'location',
        'status',
        'is_featured',
    ];

    protected function casts(): array
    {
        return [
            'event_date' => 'date',
            'event_time' => 'datetime',
            'is_featured' => 'boolean',
        ];
    }

    /**
     * Scope untuk event yang akan datang
     */
    public function scopeUpcoming($query)
    {
        return $query->where('event_date', '>=', now()->toDateString())
                    ->where('status', 'upcoming')
                    ->orderBy('event_date', 'asc');
    }

    /**
     * Scope untuk featured events
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }
}
