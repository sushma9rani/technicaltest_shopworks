<?php

namespace Tests\Unit\Model\Shop\Rota;

use App\Model\Shop\Rota\Shift;
use App\Service\Period\Period;
use PHPUnit\Framework\MockObject\MockObject;
use Tests\TestCase;

/**
 * @see \App\Model\Shop\Rota\Shift
 */
class ShiftTest extends TestCase
{
    /**
     * @param array $expect
     * @param Period $Period
     */
    public function assertPeriod(array $expect, Period $Period)
    {
        $this->assertSame(
            $expect,
            [
                $Period->getStartDateTime()->format('Y-m-d H:i:s'),
                $Period->getEndDateTime()->format('Y-m-d H:i:s'),
            ]
        );
    }

    /**
     * @see \App\Model\Shop\Rota\Shift::getWorkPeriods
     */
    public function testGetWorkPeriods()
    {
        $ShiftBreakMock = new \StdClass();
        $ShiftBreakMock->start_time = '2020-06-05 12:00:00';
        $ShiftBreakMock->end_time = '2020-06-05 13:00:00';

        /** @var MockObject|Shift $ShiftMock */
        $ShiftMock = $this->getMockBuilder(Shift::class)
            ->disableOriginalConstructor()
            ->setMethods(['shiftBreaks'])
            ->getMock();

        $ShiftMock->start_time = '2020-06-05 10:00:00';
        $ShiftMock->end_time = '2020-06-05 15:00:00';

        $ShiftMock->expects($this->once())
            ->method('shiftBreaks')
            ->willReturn([$ShiftBreakMock]);

        $PeriodCollector = $ShiftMock->getWorkPeriods();
        $periods = $PeriodCollector->getPeriods();

        $this->assertSame(2, count($periods));
        $this->assertPeriod(['2020-06-05 10:00:00', '2020-06-05 12:00:00'], $periods[0]);
        $this->assertPeriod(['2020-06-05 13:00:00', '2020-06-05 15:00:00'], $periods[1]);
    }
}
