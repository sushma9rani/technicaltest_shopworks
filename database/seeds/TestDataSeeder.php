<?php

use App\Model\Shop\Rota\Rota;
use App\Model\Shop\Rota\Shift;
use App\Model\Shop\Rota\ShiftBreak;
use App\Model\Shop\Shop;
use App\Model\Shop\Staff;
use Illuminate\Database\Seeder;

class TestDataSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Shop::truncate();
        Shop::create(['id' => 1, 'name' => 'FunHouse']);

        Staff::truncate();
        Staff::create(['id' => 1, 'first_name' => 'Black Widow', 'surname' => 'SuperHero', 'shop_id' => 1]);
        Staff::create(['id' => 2, 'first_name' => 'Thor', 'surname' => 'SuperHero', 'shop_id' => 1]);
        Staff::create(['id' => 3, 'first_name' => 'Wolverine', 'surname' => 'SuperHero', 'shop_id' => 1]);
        Staff::create(['id' => 4, 'first_name' => 'Gamora', 'surname' => 'SuperHero', 'shop_id' => 1]);

        Rota::truncate();
        Rota::create(['id' => 1, 'shop_id' => 1, 'week_commence_date' => '2020-06-01']);
        Rota::create(['id' => 2, 'shop_id' => 1, 'week_commence_date' => '2020-06-08']);

        Shift::truncate();
        ShiftBreak::truncate();

        // Scenario One
        // Black Widow: |----------------------|
        Shift::create(
            [
                'id' => 1,
                'rota_id' => 1,
                'staff_id' => 1,
                'start_time' => '2020-06-01 10:00:00',
                'end_time' => '2020-06-01 18:00:00'
            ]
        );

        // Scenario Two
        // Black Widow: |----------|
        // Thor:                   |-------------|
        Shift::create(
            [
                'id' => 2,
                'rota_id' => 1,
                'staff_id' => 1,
                'start_time' => '2020-06-02 10:00:00',
                'end_time' => '2020-06-02 13:00:00'
            ]
        );
        Shift::create(
            [
                'id' => 3,
                'rota_id' => 1,
                'staff_id' => 2,
                'start_time' => '2020-06-02 13:00:00',
                'end_time' => '2020-06-02 18:00:00'
            ]
        );

        // Scenario Three
        // Wolverine: |------------|
        // Gamora:       |-----------------|
        Shift::create(
            [
                'id' => 4,
                'rota_id' => 1,
                'staff_id' => 3,
                'start_time' => '2020-06-03 10:00:00',
                'end_time' => '2020-06-03 16:00:00'
            ]
        );
        Shift::create(
            [
                'id' => 5,
                'rota_id' => 1,
                'staff_id' => 4,
                'start_time' => '2020-06-03 11:00:00',
                'end_time' => '2020-06-03 18:00:00'
            ]
        );
    }
}
