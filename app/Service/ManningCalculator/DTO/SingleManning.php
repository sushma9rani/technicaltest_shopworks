<?php

namespace App\Service\ManningCalculator\DTO;

class SingleManning
{
    /**
     * @var \DateTime
     */
    protected $WeekCommenceDate;

    /**
     * Contains the number of minutes worked alone in the shop each day of the week.
     * example: [date => [staffId => minutes, ... ], ... ]
     * @var array
     */
    protected $staffMinutesByDate = [];

    /**
     * @param \DateTime $WeekCommenceDate
     * @param array $staffMinutesByDate
     */
    public function __construct($WeekCommenceDate, array $staffMinutesByDate)
    {
        $this->WeekCommenceDate = $WeekCommenceDate;
        $this->setStaffMinutesByDate($staffMinutesByDate);
    }

    /**
     * @param array $staffMinutesByDate
     */
    protected function setStaffMinutesByDate(array $staffMinutesByDate)
    {
        $timestamp = $this->WeekCommenceDate->getTimestamp();
        for ($i = 0; $i < 7; $i++) {
            $date = gmdate('Y-m-d', $timestamp + $i * 86400);
            $this->staffMinutesByDate[$date] = $staffMinutesByDate[$date] ?? [];
        }
    }

    /**
     * @return \DateTime
     */
    public function getWeekCommenceDate(): \DateTime
    {
        return $this->WeekCommenceDate;
    }

    /**
     * @return array
     */
    public function getStaffMinutesByDate(): array
    {
        return $this->staffMinutesByDate;
    }

    /**
     * @param string $date
     * @return int
     */
    public function getMinutesByDate(string $date): array
    {
        return $this->staffMinutesByDate[$date] ?? [];
    }

    /**
     * @param int $staffId
     * @return array
     */
    public function getWeekMinutesByStaffId(int $staffId): array
    {
        $week = [];
        foreach ($this->staffMinutesByDate as $date => $staffMinutes) {
            if (isset($staffMinutes[$staffId])) {
                $week[$date] = $staffMinutes[$staffId];
            }
        }
        return $week;
    }
}
