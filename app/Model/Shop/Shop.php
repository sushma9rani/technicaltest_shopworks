<?php

namespace App\Model\Shop;

use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    protected $table = 'shops';

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    protected $fillable = [
        'name',
    ];
}
