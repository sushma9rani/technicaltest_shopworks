<?php

namespace App\Model\Shop\Rota;

use Illuminate\Database\Eloquent\Model;

class Rota extends Model
{
    protected $table = 'rotas';

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    protected $fillable = [
        'shop_id',
        'week_commence_date',
    ];

    public function shifts()
    {
        return $this->hasMany(Shift::class)->get() ?: [];
    }

    /**
     * @return array
     */
    public function shiftsByDate(): array
    {
        $days = [];
        $shifts = $this->shifts();
        foreach ($shifts as $Shift) {
            $date = $Shift->getDate();
            if (isset($days[$date])) {
                $days[$date][] = $Shift;
            } else {
                $days[$date] = [$Shift];
            }
        }
        return $days;
    }
}
