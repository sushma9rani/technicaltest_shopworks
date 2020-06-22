<?php

namespace Tests\Unit\Model\Shop\Rota;

use App\Model\Shop\Rota\Rota;
use App\Model\Shop\Rota\Shift;
use PHPUnit\Framework\MockObject\MockObject;
use Tests\TestCase;

/**
 * @see \App\Model\Shop\Rota\Rota
 */
class RotaTest extends TestCase
{
    /**
     * @see \App\Model\Shop\Rota\Rota::shiftsByDate
     */
    public function testShiftsByDate()
    {
        $dates = [
            1 => '2020-06-10',
            2 => '2020-06-10',
            3 => '2020-06-12',
        ];

        $shifts = [];
        for ($i = 1; $i <= 3; $i++) {
            $ShiftMock = $this->getMockBuilder(Shift::class)
                ->disableOriginalConstructor()
                ->setMethods(['getDate'])
                ->getMock();

            $ShiftMock->expects($this->any())
                ->method('getDate')
                ->willReturn($dates[$i]);

            $shifts[] = $ShiftMock;
        }

        /** @var MockObject|Rota $RotaMock */
        $RotaMock = $this->getMockBuilder(Rota::class)
            ->disableOriginalConstructor()
            ->setMethods(['shifts'])
            ->getMock();

        $RotaMock->expects($this->once())
            ->method('shifts')
            ->willReturn($shifts);

        $result = $RotaMock->shiftsByDate();
        $this->assertSame(
            [
                '2020-06-10' => [$shifts[0], $shifts[1]],
                '2020-06-12' => [$shifts[2]],
            ],
            $result
        );
    }

    /**
     * @see \App\Model\Shop\Rota\Rota::shiftsByDate
     */
    public function testShiftsByDateEmpty()
    {
        /** @var MockObject|Rota $RotaMock */
        $RotaMock = $this->getMockBuilder(Rota::class)
            ->disableOriginalConstructor()
            ->setMethods(['shifts'])
            ->getMock();

        $RotaMock->expects($this->once())
            ->method('shifts')
            ->willReturn([]);

        $result = $RotaMock->shiftsByDate();
        $this->assertSame([], $result);
    }
}
