<?php

namespace App\Service\ManningCalculator;

use App\Model\Shop\Rota\Rota;
use App\Model\Shop\Rota\Shift;
use App\Service\ManningCalculator\DTO\SingleManning;
use App\Service\Period\PeriodCollection;
use App\Service\Period\PeriodFactory;

class ManningCalculator
{
    /**
     * @param Rota $Rota
     * @return SingleManning
     */
    public function getSimpleManning(Rota $Rota): SingleManning
    {
        $staffMinutesByDate = [];

        if ($shiftsByDate = $Rota->shiftsByDate()) {
            foreach ($shiftsByDate as $date => $shifts) {
                $staffMinutesByDate[$date] = $this->getStaffSingleMinutesByShift($shifts);
            }
        }

        return new SingleManning(
            new \DateTime($Rota->week_commence_date, new \DateTimeZone('+0000')),
            $staffMinutesByDate
        );
    }

    /**
     * @param Shift[] $shifts
     * @return array
     */
    protected function getStaffSingleMinutesByShift(array $shifts): array
    {
        /** @var PeriodCollection $staffWorkPeriods */
        $staffWorkPeriods = [];
        $DayPeriodsCollection = PeriodFactory::createCollection();
        foreach ($shifts as $Shift) {
            /** @var Shift $Shift */
            if ($StaffDayWorkPeriodsCollection = $Shift->getWorkPeriods()) {
                $staffWorkPeriods[$Shift->staff_id] = $StaffDayWorkPeriodsCollection;
                $DayPeriodsCollection->addPeriods($StaffDayWorkPeriodsCollection);
            }
        }

        $uniquePeriods = $DayPeriodsCollection->getUniquePeriods()->getPeriods();

        $minutes = [];

        if ($uniquePeriods) {
            foreach ($staffWorkPeriods as $staffId => $WorkPeriodsCollection) {
                /** @var PeriodCollection $WorkPeriodsCollection */
                foreach ($uniquePeriods as $key => $UniquePeriod) {
                    if ($WorkPeriodsCollection->isCoverPeriod($UniquePeriod)) {
                        unset($uniquePeriods[$key]);
                        $minutes[$staffId] = ($minutes[$staffId] ?? 0) + $UniquePeriod->getMinutes();
                    }
                }
            }
        }

        return $minutes;
    }
}
