<?php

namespace App\Model\Shop\Rota;

use App\Model\Shop\Staff;
use App\Service\Period\PeriodCollection;
use App\Service\Period\PeriodFactory;
use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    protected $table = 'shifts';

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    protected $fillable = [
        'rota_id',
        'staff_id',
        'start_time',
        'end_time',
    ];

    public function getDate(): string
    {
        return substr($this->start_time, 0, 10);
    }

    public function shiftBreaks()
    {
        return $this->hasMany(ShiftBreak::class)->get();
    }

    public function staff()
    {
        return $this->hasOne(Staff::class)->get();
    }

    public function getWorkPeriods(): PeriodCollection
    {
        $PeriodCollection = PeriodFactory::createCollection();
        $PeriodCollection->addPeriods(PeriodFactory::createPeriod($this->start_time, $this->end_time));

        if ($shiftBreaks = $this->shiftBreaks()) {
            /** @var ShiftBreak $ShiftBreak */
            foreach ($shiftBreaks as $ShiftBreak) {
                $PeriodCollection = $PeriodCollection->split(
                    PeriodFactory::createPeriod($ShiftBreak->start_time, $ShiftBreak->end_time)
                );
            }
        }

        return $PeriodCollection;
    }
}
