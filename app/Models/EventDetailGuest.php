<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventDetailGuest extends Model
{
    protected $fillable = [
        'event_detail_id',
        'name',
        'jersey_name',
        'jersey_number',
        'size',
        'custom_width',
        'custom_height',
        'sleeve_type'
    ];

    public function eventDetail()
    {
        return $this->belongsTo(EventDetail::class);
    }
}
