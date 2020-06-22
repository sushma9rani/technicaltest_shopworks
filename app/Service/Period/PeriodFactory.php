<?php

namespace App\Service\Period;

class PeriodFactory
{
    /**
     * @param string $start
     * @param string $end
     * @return Period
     */
    public static function createPeriod(string $start, string $end): Period
    {
        if (is_numeric($start)) {
            $start = '@' . $start;
        }
        if (is_numeric($end)) {
            $end = '@' . $end;
        }
        return new Period(
            new \DateTime($start, new \DateTimeZone('+0000')),
            new \DateTime($end, new \DateTimeZone('+0000'))
        );
    }

    /**
     * @return PeriodCollection
     */
    public static function createCollection(): PeriodCollection
    {
        return new PeriodCollection();
    }
}
