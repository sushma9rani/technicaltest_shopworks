<?php

namespace App\Service\Period;

class PeriodCollection
{
    /**
     * @var Period[]
     */
    protected $periods = [];

    /**
     * @param Period[]|PeriodCollection[] ...$periods
     */
    public function addPeriods(...$periods)
    {
        foreach ($periods as $Period) {
            if ($Period instanceof PeriodCollection) {
                $this->addPeriods(...$Period->getPeriods());
                continue;
            }
            if ($Period instanceof Period) {
                $this->periods[] = $Period;
                continue;
            }
        }
    }

    /**
     * @param Period $Separator
     * @return PeriodCollection
     */
    public function split(Period $Separator): PeriodCollection
    {
        $NewPeriodCollection = PeriodFactory::createCollection();

        foreach ($this->periods as $Period) {
            $NewPeriodCollection->addPeriods($Period->split($Separator));
        }

        return $NewPeriodCollection;
    }

    /**
     * @return PeriodCollection
     */
    public function getSinglePeriods(): PeriodCollection
    {
        $points = [];
        foreach ($this->periods as $Period) {
            $points[$Period->getStartDateTime()->getTimestamp()] = true;
            $points[$Period->getEndDateTime()->getTimestamp()] = true;
        }

        $points = array_keys($points);
        sort($points);

        $NewPeriodCollection = PeriodFactory::createCollection();
        for ($i = 0, $count = count($points); $i < $count - 1; $i++) {
            $NewPeriodCollection->addPeriods(
                PeriodFactory::createPeriod($points[$i], $points[$i+1])
            );
        }

        return $NewPeriodCollection;
    }

    /**
     * @return PeriodCollection
     */
    public function getUniquePeriods(): PeriodCollection
    {
        $singlePeriods = $this->getSinglePeriods()->getPeriods();

        $hash = [];
        $periods = $this->periods;
        foreach ($periods as $Period) {
            foreach ($singlePeriods as $key => $SinglePeriod) {
                if ($Period->isCoverPeriod($SinglePeriod)) {
                    $hash[$key] = isset($hash[$key]) ? $hash[$key] + 1 : 1;
                    if ($hash[$key] > 1) {
                        // Remove not unique periods
                        unset($hash[$key], $singlePeriods[$key]);
                    }
                }
            }
        }

        $UniquePeriodsCollection = PeriodFactory::createCollection();
        foreach ($hash as $key => $count) {
            if ($count === 1) {
                $UniquePeriodsCollection->addPeriods($singlePeriods[$key]);
            }
        }

        return $UniquePeriodsCollection;
    }

    /**
     * @param Period $CoveredPeriod
     * @return bool
     */
    public function isCoverPeriod(Period $CoveredPeriod): bool
    {
        foreach ($this->periods as $Period) {
            if ($Period->isCoverPeriod($CoveredPeriod)) {
                return true;
            }
        }
        return false;
    }

    /**
     * @return Period[]
     */
    public function getPeriods(): array
    {
        return $this->periods;
    }

    /**
     * @return int
     */
    public function getMinutes(): int
    {
        $minutes = 0;
        foreach ($this->periods as $Period) {
            $minutes += $Period->getMinutes();
        }

        return $minutes;
    }
}
