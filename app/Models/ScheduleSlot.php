<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScheduleSlot extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'time',
        'is_available',
        'location_id',
        'available_date',

    ];

    public function location()
    {
        return $this->belongsTo(Location::class);
    }
}