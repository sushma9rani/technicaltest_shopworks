<?php

namespace App\Model\Shop\Rota;

use Illuminate\Database\Eloquent\Model;

class ShiftBreak extends Model
{
    protected $table = 'shift_breaks';

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    protected $fillable = [
        'staff_id',
        'start_time',
        'end_time',
    ];

    public $timestamps = false;
}
