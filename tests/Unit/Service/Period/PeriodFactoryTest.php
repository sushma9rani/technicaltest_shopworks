<?php

namespace Tests\Unit\Service\Period;

use App\Service\Period\Period;
use App\Service\Period\PeriodCollection;
use App\Service\Period\PeriodFactory;
use Tests\TestCase;

/**
 * @see \App\Service\Period\PeriodFactory
 */
class PeriodFactoryTest extends TestCase
{
    public function providerTestCreatePeriod()
    {
        return [
            'line_' . __LINE__ => [
                'startDate' => 1590973210,
                'endDate' => 1590976810,
                'expectStartDate' => '2020-06-01 01:00:10 0',
                'expectEndDate' => '2020-06-02 02:00:10 0',
            ],
            'line_' . __LINE__ => [
                'startDate' => '2019-10-10 10:10:10',
                'endDate' => '2019-12-12 12:12:12',
                'expectStartDate' => '2019-10-10 10:10:10 0',
                'expectEndDate' => '2019-12-12 12:12:12 0',
            ],
        ];
    }

    /**
     * @see \App\Service\Period\PeriodFactory::createPeriod
     * @dataProvider providerTestCreatePeriod
     */
    public function testCreatePeriod($startDate, $endDate, $expectStartDate, $expectEndDate)
    {
        $Period = PeriodFactory::createPeriod($startDate, $endDate);
        $this->assertTrue($Period instanceof Period);

        $this->assertSame($expectStartDate, $Period->getStartDateTime()->format('Y-m-d H:i:s Z'));
        $this->assertSame($expectEndDate, $Period->getEndDateTime()->format('Y-m-d H:i:s Z'));
    }

    /**
     * @see \App\Service\Period\PeriodFactory::createCollection
     */
    public function testCreateCollection()
    {
        $PeriodCollection = PeriodFactory::createCollection();
        $this->assertTrue($PeriodCollection instanceof PeriodCollection);
    }
}
