# Shopworks Technical Test

### Tech stack

- PHP 7.2
- Laravel
- PHPUnit

### How to run tests

Run `./vendor/bin/phpunit`

###Task Implementations

> Your task is to **implement a class** that receives a `Rota` and returns `SingleManning`, a DTO (Data Transfer Object) containing the __number of minutes worked alone in the shop each day of the week__.

Please see class `\App\Service\ManningCalculator\ManningCalculator`

> You'll find a `migration.php` file attached, which is a standard Laravel migration file describing the data structure - ** you do not need to implement this migration or models as part of the code test. They are just for reference**

Used the migration file to create tables
Run 'php artisan migrate' to get all the tables

> Make sure that all the above scenarios are proven to work.
> We would like for you to describe another scenario, involving at least three people and implement it too.
If you think of one more scenario and/or implement it, that would be a plus.

Please see `\Tests\Feature\Service\ManningCalculator\ManningCalculatorTest`

> You must include tests.

Included

Run `./vendor/bin/phpunit ./tests/Feature/Service/ManningCalculator/ManningCalculatorTest.php`

> Please only include the files absolutely necessary to complete the task and run the tests.

Included full laravel folder
