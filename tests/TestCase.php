<?php

declare(strict_types=1);

namespace Sarala;

use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Support\Facades\Route;
use Orchestra\Testbench\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');
        $this->artisan('migrate', ['--database' => 'testbench']);
        $this->withFactories(__DIR__.'/database/factories');
        $this->registerRoutes();
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('sarala.base_url', 'https://sarala-demo.app/api');
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
    }

    protected function getPackageProviders($app)
    {
        return [
            JsonApiServiceProvider::class,
        ];
    }

    protected function registerRoutes(): void
    {
        Route::namespace('Sarala\Dummy\Http\Controllers')
            ->middleware([SubstituteBindings::class])
            ->group(function () {
                require __DIR__.'/routes.php';
            });
    }
}
