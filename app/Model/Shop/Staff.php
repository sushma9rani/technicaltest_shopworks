<?php

namespace App\Model\Shop;

use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    protected $table = 'staff';

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    protected $fillable = [
        'first_name',
        'surname',
        'shop_id',
    ];
}
