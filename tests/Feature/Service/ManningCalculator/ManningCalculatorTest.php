<?php

namespace Tests\Feature\Service\ManningCalculator;

use App\Model\Shop\Rota\Rota;
use App\Service\ManningCalculator\DTO\SingleManning;
use App\Service\ManningCalculator\ManningCalculator;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class ManningCalculatorTest extends TestCase
{
    public static $isDatabaseReady = false;

    public function setUp()
    {
        parent::setUp();
        if (!self::$isDatabaseReady) {
            $this->setupDatabase();
        }
    }

    public function setupDatabase()
    {
        Artisan::call('migrate');
        Artisan::call('db:seed', ['--class' => \TestDataSeeder::class]);
        self::$isDatabaseReady = true;
    }

    /**
     * @return SingleManning
     */
    protected function getSimpleManning($rotaId): SingleManning
    {
        $Rota = Rota::find($rotaId);
        $this->assertTrue($Rota instanceof Rota);

        $ManningCalculator = new ManningCalculator();
        $SimpleManning = $ManningCalculator->getSimpleManning($Rota);
        return $SimpleManning;
    }

    /**
     * @see \TestDataSeeder
     */
    public function testScenarioOne()
    {
        // Black Widow: |----------------------|
        $this->assertSame([1 => 480,], $this->getSimpleManning(1)->getMinutesByDate('2020-06-01'));
    }

    /**
     * @see \TestDataSeeder
     */
    public function testScenarioTwo()
    {
        // Black Widow: |----------|
        // Thor:                   |-------------|
        $this->assertSame([1 => 180, 2 => 300], $this->getSimpleManning(1)->getMinutesByDate('2020-06-02'));
    }

    /**
     * @see \TestDataSeeder
     */
    public function testScenarioThree()
    {
        // Wolverine: |------------|
        // Gamora:       |-----------------|
        $this->assertSame([3 => 60, 4 => 120], $this->getSimpleManning(1)->getMinutesByDate('2020-06-03'));
    }
    
    public function testScenarioEmpty()
    {
        $this->assertSame([], $this->getSimpleManning(1)->getMinutesByDate('2020-06-09'));
        $this->assertSame([], $this->getSimpleManning(2)->getMinutesByDate('2020-06-09'));
    }
}
