<?php

use App\Model\Shop\Rota\Rota;
use App\Model\Shop\Rota\Shift;
use App\Service\ManningCalculator\ManningCalculator;
use Illuminate\Foundation\Inspiring;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->describe('Display an inspiring quote');


Artisan::command('tt', function () {

    /** @var Rota $Rota */
    $Rota = Rota::find(1);
    /** @var Shift[] $shifts */
    $shifts = $Rota->shifts();

    $ManningCalculator = new ManningCalculator();
    $SingleManning = $ManningCalculator->getSimpleManning($Rota);
    print_r($SingleManning);
    echo PHP_EOL;

})->describe('er');
