<?php

namespace App\Service\Period;

class Period
{
    /**
     * @var \DateTime
     */
    protected $StartDateTime;

    /**
     * @var \DateTime
     */
    protected $EndDateTime;

    /**
     * @param \DateTime $StartDateTime
     * @param \DateTime $EndDateTime
     */
    public function __construct(\DateTime $StartDateTime, \DateTime $EndDateTime)
    {
        $this->StartDateTime = $StartDateTime;
        $this->EndDateTime = $EndDateTime;
    }

    /**
     * @return \DateTime
     */
    public function getStartDateTime(): \DateTime
    {
        return $this->StartDateTime;
    }

    /**
     * @return \DateTime
     */
    public function getEndDateTime(): \DateTime
    {
        return $this->EndDateTime;
    }

    /**
     * @return int
     */
    public function getMinutes(): int
    {
        $secondsInterval = max(0, $this->EndDateTime->getTimestamp() - $this->StartDateTime->getTimestamp());
        return (int)round($secondsInterval / 60);
    }

    /**
     * @param Period $Period
     * @return PeriodCollection
     */
    public function split(Period $Period): PeriodCollection
    {
        $PeriodCollection = new PeriodCollection();

        // 1. Beginning
        $startPoint = min($this->StartDateTime->getTimestamp(), $Period->getEndDateTime()->getTimestamp());
        $endPoint = min($this->EndDateTime->getTimestamp(), $Period->getStartDateTime()->getTimestamp());
        if ($startPoint < $endPoint) {
            $PeriodCollection->addPeriods(PeriodFactory::createPeriod($startPoint, $endPoint));
        }

        // 2. End
        $startPoint = max($this->StartDateTime->getTimestamp(), $Period->getEndDateTime()->getTimestamp());
        $endPoint = max($this->EndDateTime->getTimestamp(), $Period->getStartDateTime()->getTimestamp());
        if ($startPoint < $endPoint) {
            $PeriodCollection->addPeriods(PeriodFactory::createPeriod($startPoint, $endPoint));
        }

        return $PeriodCollection;
    }

    /**
     * @param Period $CoveredPeriod
     * @return bool
     */
    public function isCoverPeriod(Period $CoveredPeriod): bool
    {
        return $CoveredPeriod->getStartDateTime()->getTimestamp() >= $this->getStartDateTime()->getTimestamp()
            && $CoveredPeriod->getEndDateTime()->getTimestamp() <= $this->getEndDateTime()->getTimestamp();
    }
}
