<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'title',
        'logo',
        'start_date',
        'end_date',
        'status',
        'event_money'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function eventDetails()
    {
        return $this->hasMany(EventDetail::class);
    }
}
