<?php

namespace Kpebedko22\FilamentYandexMap\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;
use Kpebedko22\FilamentYandexMap\FilamentYandexMapServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'Kpebedko22\\FilamentYandexMap\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );
    }

    protected function getPackageProviders($app)
    {
        return [
            FilamentYandexMapServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');

        /*
        $migration = include __DIR__.'/../database/migrations/create_filament-yandex-map_table.php.stub';
        $migration->up();
        */
    }
}
