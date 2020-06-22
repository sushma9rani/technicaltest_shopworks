<?php

namespace Tests\Unit\Service\Period;

use App\Service\Period\Period;
use App\Service\Period\PeriodCollection;
use App\Service\Period\PeriodFactory;
use Tests\TestCase;

/**
 * @see \App\Service\Period\PeriodCollection
 */
class PeriodCollectionTest extends TestCase
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
     * @param array $periods
     * @return PeriodCollection
     */
    public function createCollection(array $periods): PeriodCollection
    {
        $PeriodCollection = PeriodFactory::createCollection();
        foreach ($periods as $period) {
            $PeriodCollection->addPeriods(PeriodFactory::createPeriod($period[0], $period[1]));
        }
        return $PeriodCollection;
    }

    /**
     * @see \App\Service\Period\PeriodCollection::addPeriods
     */
    public function testAddPeriods()
    {
        $Period1 = PeriodFactory::createPeriod('2020-06-01 10:00:00', '2020-06-01 11:00:00');
        $Period2 = PeriodFactory::createPeriod('2020-06-01 12:00:00', '2020-06-01 13:00:00');
        $Period3 = PeriodFactory::createPeriod('2020-06-01 14:00:00', '2020-06-01 15:00:00');

        $PeriodCollection1 = PeriodFactory::createCollection();
        $PeriodCollection1->addPeriods($Period1);
        $PeriodCollection1->addPeriods($Period2);
        $PeriodCollection1->addPeriods($Period3);

        $this->assertSame([$Period1, $Period2, $Period3], $PeriodCollection1->getPeriods());

        $PeriodCollection2 = PeriodFactory::createCollection();
        $PeriodCollection2->addPeriods($Period1, $Period2, $Period3);

        $this->assertSame([$Period1, $Period2, $Period3], $PeriodCollection2->getPeriods());

        $PeriodCollection3 = PeriodFactory::createCollection();
        $PeriodCollection3->addPeriods($PeriodCollection1);

        $this->assertSame([$Period1, $Period2, $Period3], $PeriodCollection3->getPeriods());
    }

    /**
     * @see \App\Service\Period\PeriodCollection::split
     */
    public function testSplit()
    {
        $PeriodCollection = PeriodFactory::createCollection();
        $PeriodCollection->addPeriods(PeriodFactory::createPeriod('2020-06-01 10:00:00', '2020-06-01 18:00:00'));
        $PeriodCollection->addPeriods(PeriodFactory::createPeriod('2020-06-01 12:00:00', '2020-06-01 20:00:00'));
        $NewPeriodCollection = $PeriodCollection->split(
            PeriodFactory::createPeriod('2020-06-01 14:00:00', '2020-06-01 15:00:00')
        );

        $periods = $NewPeriodCollection->getPeriods();
        $this->assertSame(4, count($periods));

        $this->assertPeriod(['2020-06-01 10:00:00', '2020-06-01 14:00:00'], $periods[0]);
        $this->assertPeriod(['2020-06-01 15:00:00', '2020-06-01 18:00:00'], $periods[1]);
        $this->assertPeriod(['2020-06-01 12:00:00', '2020-06-01 14:00:00'], $periods[2]);
        $this->assertPeriod(['2020-06-01 15:00:00', '2020-06-01 20:00:00'], $periods[3]);
    }

    public function providerTestGetSinglePeriods()
    {
        return [
            'line_' . __LINE__ => [
                'periods' => [],
                'expect' => [],
            ],
            'line_' . __LINE__ => [
                'periods' => [
                    ['2020-06-01 10:00:00', '2020-06-01 14:00:00'],
                ],
                'expect' => [
                    ['2020-06-01 10:00:00', '2020-06-01 14:00:00'],
                ],
            ],
            'line_' . __LINE__ => [
                'periods' => [
                    ['2020-06-01 10:00:00', '2020-06-01 14:00:00'],
                    ['2020-06-01 14:00:00', '2020-06-01 18:00:00'],
                ],
                'expect' => [
                    ['2020-06-01 10:00:00', '2020-06-01 14:00:00'],
                    ['2020-06-01 14:00:00', '2020-06-01 18:00:00'],
                ],
            ],
            'line_' . __LINE__ => [
                'periods' => [
                    ['2020-06-01 10:00:00', '2020-06-01 14:00:00'],
                    ['2020-06-01 10:00:00', '2020-06-01 18:00:00'],
                ],
                'expect' => [
                    ['2020-06-01 10:00:00', '2020-06-01 14:00:00'],
                    ['2020-06-01 14:00:00', '2020-06-01 18:00:00'],
                ],
            ],
            'line_' . __LINE__ => [
                'periods' => [
                    ['2020-06-01 10:00:00', '2020-06-01 20:00:00'],
                    ['2020-06-01 12:00:00', '2020-06-01 18:00:00'],
                    ['2020-06-01 14:00:00', '2020-06-01 16:00:00'],
                ],
                'expect' => [
                    ['2020-06-01 10:00:00', '2020-06-01 12:00:00'],
                    ['2020-06-01 12:00:00', '2020-06-01 14:00:00'],
                    ['2020-06-01 14:00:00', '2020-06-01 16:00:00'],
                    ['2020-06-01 16:00:00', '2020-06-01 18:00:00'],
                    ['2020-06-01 18:00:00', '2020-06-01 20:00:00'],
                ],
            ],
        ];
    }

    /**
     * @see \App\Service\Period\PeriodCollection::getSinglePeriods
     * @dataProvider providerTestGetSinglePeriods
     */
    public function testGetSinglePeriods($periods, $expect)
    {
        $PeriodCollection = $this->createCollection($periods);

        $SinglePeriodsCollection = $PeriodCollection->getSinglePeriods();
        $periods = $SinglePeriodsCollection->getPeriods();

        $this->assertSame(count($expect), count($periods));
        foreach ($periods as $key => $Period) {
            $this->assertPeriod($expect[$key], $Period);
        }
    }

    public function providerTestGetUniquePeriods()
    {
        return [
            'line_' . __LINE__ => [
                'periods' => [],
                'expect' => [],
            ],
            'line_' . __LINE__ => [
                'periods' => [
                    ['2020-06-01 10:00:00', '2020-06-01 14:00:00'],
                ],
                'expect' => [
                    ['2020-06-01 10:00:00', '2020-06-01 14:00:00'],
                ],
            ],
            'line_' . __LINE__ => [
                'periods' => [
                    ['2020-06-01 10:00:00', '2020-06-01 14:00:00'],
                    ['2020-06-01 10:00:00', '2020-06-01 14:00:00'],
                ],
                'expect' => [],
            ],
            'line_' . __LINE__ => [
                'periods' => [
                    ['2020-06-01 10:00:00', '2020-06-01 14:00:00'],
                    ['2020-06-01 14:00:00', '2020-06-01 16:00:00'],
                ],
                'expect' => [
                    ['2020-06-01 10:00:00', '2020-06-01 14:00:00'],
                    ['2020-06-01 14:00:00', '2020-06-01 16:00:00'],
                ],
            ],
            'line_' . __LINE__ => [
                'periods' => [
                    ['2020-06-01 10:00:00', '2020-06-01 14:00:00'],
                    ['2020-06-01 12:00:00', '2020-06-01 16:00:00'],
                ],
                'expect' => [
                    ['2020-06-01 10:00:00', '2020-06-01 12:00:00'],
                    ['2020-06-01 14:00:00', '2020-06-01 16:00:00'],
                ],
            ],
            'line_' . __LINE__ => [
                'periods' => [
                    ['2020-06-01 10:00:00', '2020-06-01 20:00:00'],
                    ['2020-06-01 12:30:00', '2020-06-01 18:30:00'],
                ],
                'expect' => [
                    ['2020-06-01 10:00:00', '2020-06-01 12:30:00'],
                    ['2020-06-01 18:30:00', '2020-06-01 20:00:00'],
                ],
            ],
        ];
    }

    /**
     * @see \App\Service\Period\PeriodCollection::getUniquePeriods
     * @dataProvider providerTestGetUniquePeriods
     */
    public function testGetUniquePeriods($periods, $expect)
    {
        $PeriodCollection = $this->createCollection($periods);

        $SinglePeriodsCollection = $PeriodCollection->getUniquePeriods();
        $periods = $SinglePeriodsCollection->getPeriods();

        $this->assertSame(count($expect), count($periods));
        foreach ($periods as $key => $Period) {
            $this->assertPeriod($expect[$key], $Period);
        }
    }

    public function providerTestIsCoverPeriod()
    {
        return [
            'line_' . __LINE__ => [
                'periods' => [],
                'coverPeriod' => ['2020-06-01 11:00:00', '2020-06-01 12:00:00'],
                'expect' => false
            ],
            'line_' . __LINE__ => [
                'periods' => [
                    ['2020-06-01 11:00:00', '2020-06-01 12:00:00'],
                ],
                'coverPeriod' => ['2020-06-01 11:00:00', '2020-06-01 12:00:00'],
                'expect' => true
            ],
            'line_' . __LINE__ => [
                'periods' => [
                    ['2020-06-01 10:00:00', '2020-06-01 14:00:00'],
                    ['2020-06-01 13:00:00', '2020-06-01 14:00:00'],
                ],
                'coverPeriod' => ['2020-06-01 11:00:00', '2020-06-01 12:00:00'],
                'expect' => true
            ],
            'line_' . __LINE__ => [
                'periods' => [
                    ['2020-06-01 10:00:00', '2020-06-01 11:30:00'],
                    ['2020-06-01 11:30:00', '2020-06-01 12:00:00'],
                ],
                'coverPeriod' => ['2020-06-01 11:00:00', '2020-06-01 12:00:00'],
                'expect' => false
            ],
        ];
    }

    /**
     * @see \App\Service\Period\PeriodCollection::isCoverPeriod
     * @dataProvider providerTestIsCoverPeriod
     */
    public function testIsCoverPeriod($periods, $coverPeriod, $expect)
    {
        $PeriodCollection = $this->createCollection($periods);

        $result = $PeriodCollection->isCoverPeriod(PeriodFactory::createPeriod($coverPeriod[0], $coverPeriod[1]));

        $this->assertSame($expect, $result);
    }

    public function providerTestGetMinutes()
    {
        return [
            'line_' . __LINE__ => [
                'periods' => [],
                'expect' => 0
            ],
            'line_' . __LINE__ => [
                'periods' => [
                    ['2020-06-01 10:00:00', '2020-06-01 12:00:00'],
                ],
                'expect' => 120
            ],
            'line_' . __LINE__ => [
                'periods' => [
                    ['2020-06-01 10:00:00', '2020-06-01 12:00:00'],
                    ['2020-06-01 10:00:00', '2020-06-01 12:00:00'],
                ],
                'expect' => 240
            ],
            'line_' . __LINE__ => [
                'periods' => [
                    ['2020-06-01 10:00:00', '2020-06-01 12:00:00'],
                    ['2020-06-01 14:00:00', '2020-06-01 14:30:00'],
                ],
                'expect' => 150
            ],
        ];
    }

    /**
     * @see \App\Service\Period\PeriodCollection::getMinutes
     * @dataProvider providerTestGetMinutes
     */
    public function testGetMinutes($periods, $expect)
    {
        $PeriodCollection = $this->createCollection($periods);
        $this->assertSame($expect, $PeriodCollection->getMinutes());
    }
}
