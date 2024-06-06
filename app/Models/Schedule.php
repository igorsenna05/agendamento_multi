<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_name',
        'user_cpf',
        'user_insc',
        'user_email',
        'user_phone',
        'slot_id',
        'service_type',
        'location_id',
        'confirmation_token',
    ];

    public function slot()
    {
        return $this->belongsTo(ScheduleSlot::class, 'slot_id');
    }

    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id');
    }
}
