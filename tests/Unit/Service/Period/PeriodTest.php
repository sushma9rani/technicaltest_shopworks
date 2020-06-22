<?php

namespace Tests\Unit\Service\Period;

use App\Service\Period\PeriodCollection;
use App\Service\Period\PeriodFactory;
use Tests\TestCase;

/**
 * @see \App\Service\Period\Period
 */
class PeriodTest extends TestCase
{
    public function providerTestGetMinutes()
    {
        return [
            'line_' . __LINE__ => [
                'startDate' => 0,
                'endDate' => 600,
                'expect' => 10,
            ],
            'line_' . __LINE__ => [
                'startDate' => 1000,
                'endDate' => 1900,
                'expect' => 15,
            ],
            'line_' . __LINE__ => [
                'startDate' => 0,
                'endDate' => 86400,
                'expect' => 1440,
            ],
            'line_' . __LINE__ => [
                'startDate' => '2020-06-01 10:00:00',
                'endDate' => '2020-06-01 12:00:00',
                'expect' => 120,
            ],
            'line_' . __LINE__ => [
                'startDate' => '2020-06-01 10:00:00',
                'endDate' => '2020-06-02 10:00:00',
                'expect' => 1440,
            ],
            'line_' . __LINE__ => [
                'startDate' => '2020-06-01 00:00:00',
                'endDate' => '2020-06-01 00:59:00',
                'expect' => 59,
            ],
            'line_' . __LINE__ => [
                'startDate' => '2020-06-01 00:00:00',
                'endDate' => '2020-06-01 00:59:59',
                'expect' => 60,
            ],
        ];
    }

    /**
     * @see \App\Service\Period\Period::getMinutes
     * @dataProvider providerTestGetMinutes
     */
    public function testGetMinutes($startDate, $endDate, $expect)
    {
        $Period = PeriodFactory::createPeriod($startDate, $endDate);
        $this->assertSame($expect, $Period->getMinutes());
    }

    public function providerTestIsCoverPeriod()
    {
        return [
            'line_' . __LINE__ => [
                'startDate' => '2020-05-01 10:00:00',
                'endDate' => '2020-05-01 12:00:00',
                'startDate2' => '2020-05-01 10:00:00',
                'endDate2' => '2020-05-01 12:00:00',
                'expect' => true
            ],
            'line_' . __LINE__ => [
                'startDate' => '2020-05-01 10:00:00',
                'endDate' => '2020-05-01 12:00:00',
                'startDate2' => '2020-05-01 11:00:00',
                'endDate2' => '2020-05-01 12:00:00',
                'expect' => true
            ],
            'line_' . __LINE__ => [
                'startDate' => '2020-05-01 10:00:00',
                'endDate' => '2020-05-01 12:00:00',
                'startDate2' => '2020-05-01 10:00:00',
                'endDate2' => '2020-05-01 11:00:00',
                'expect' => true
            ],
            'line_' . __LINE__ => [
                'startDate' => '2020-05-01 10:00:00',
                'endDate' => '2020-05-01 12:00:00',
                'startDate2' => '2020-05-01 11:00:00',
                'endDate2' => '2020-05-01 11:30:00',
                'expect' => true
            ],
            'line_' . __LINE__ => [
                'startDate' => '2020-05-01 10:00:00',
                'endDate' => '2020-05-01 12:00:00',
                'startDate2' => '2020-05-02 10:00:00',
                'endDate2' => '2020-05-02 11:00:00',
                'expect' => false
            ],
            'line_' . __LINE__ => [
                'startDate' => '2020-05-01 10:00:00',
                'endDate' => '2020-05-01 12:00:00',
                'startDate2' => '2020-05-01 09:00:00',
                'endDate2' => '2020-05-01 11:00:00',
                'expect' => false
            ],
            'line_' . __LINE__ => [
                'startDate' => '2020-05-01 10:00:00',
                'endDate' => '2020-05-01 12:00:00',
                'startDate2' => '2020-05-01 11:00:00',
                'endDate2' => '2020-05-01 13:00:00',
                'expect' => false
            ],
        ];
    }

    /**
     * @see \App\Service\Period\Period::isCoverPeriod
     * @dataProvider providerTestIsCoverPeriod
     */
    public function testIsCoverPeriod($startDate, $endDate, $startDate2, $endDate2, $expect)
    {
        $Period = PeriodFactory::createPeriod($startDate, $endDate);
        $Period2 = PeriodFactory::createPeriod($startDate2, $endDate2);

        $this->assertSame($expect, $Period->isCoverPeriod($Period2));
    }

    public function providerTestSplit()
    {
        return [
            'line_' . __LINE__ => [
                'period' => ['2020-05-01 10:00:00', '2020-05-01 12:00:00'],
                'split' => ['2020-05-01 10:00:00', '2020-05-01 12:00:00'],
                'expect' => [],
            ],
            'line_' . __LINE__ => [
                'period' => ['2020-05-01 10:00:00', '2020-05-01 12:00:00'],
                'split' => ['2020-05-01 09:00:00', '2020-05-01 13:00:00'],
                'expect' => [],
            ],
            'line_' . __LINE__ => [
                'period' => ['2020-05-01 10:00:00', '2020-05-01 12:00:00'],
                'split' => ['2020-05-01 10:00:00', '2020-05-01 11:00:00'],
                'expect' => [
                    ['2020-05-01 11:00:00', '2020-05-01 12:00:00'],
                ],
            ],
            'line_' . __LINE__ => [
                'period' => ['2020-05-01 10:00:00', '2020-05-01 12:00:00'],
                'split' => ['2020-05-01 11:00:00', '2020-05-01 13:00:00'],
                'expect' => [
                    ['2020-05-01 10:00:00', '2020-05-01 11:00:00'],
                ],
            ],
            'line_' . __LINE__ => [
                'period' => ['2020-05-01 10:00:00', '2020-05-01 12:00:00'],
                'split' => ['2020-05-01 10:30:00', '2020-05-01 11:30:00'],
                'expect' => [
                    ['2020-05-01 10:00:00', '2020-05-01 10:30:00'],
                    ['2020-05-01 11:30:00', '2020-05-01 12:00:00'],
                ],
            ],
            'line_' . __LINE__ => [
                'period' => ['2020-05-01 10:00:00', '2020-05-02 10:00:00'],
                'split' => ['2020-05-01 18:00:00', '2020-05-01 20:59:00'],
                'expect' => [
                    ['2020-05-01 10:00:00', '2020-05-01 18:00:00'],
                    ['2020-05-01 20:59:00', '2020-05-02 10:00:00'],
                ],
            ],
        ];
    }

    /**
     * @see \App\Service\Period\Period::split
     * @dataProvider providerTestSplit
     */
    public function testSplit($period, $split, $expect)
    {
        $Period = PeriodFactory::createPeriod($period[0], $period[1]);
        $PeriodCollection = $Period->split(PeriodFactory::createPeriod($split[0], $split[1]));
        $this->assertTrue($PeriodCollection instanceof PeriodCollection);

        $periods = $PeriodCollection->getPeriods();
        $this->assertSame(count($expect), count($periods));

        foreach ($expect as $key => $exp) {
            $this->assertSame($exp[0], $periods[$key]->getStartDateTime()->format('Y-m-d H:i:s'));
            $this->assertSame($exp[1], $periods[$key]->getEndDateTime()->format('Y-m-d H:i:s'));
        }
    }
}
