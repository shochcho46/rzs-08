<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventDetail extends Model
{
    protected $fillable = [
        'event_id',
        'name',
        'mobile',
        'jersey_name',
        'jersey_number',
        'size',
        'custom_width',
        'custom_height',
        'sleeve_type',
        'is_guest_jersey',
        'play_status'
    ];

    protected $casts = [
        'is_guest_jersey' => 'boolean',
        'play_status' => 'boolean',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function guests()
    {
        return $this->hasMany(EventDetailGuest::class);
    }
}
