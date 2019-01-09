<?php

declare(strict_types=1);

namespace Sarala;

use Illuminate\Support\Facades\Route;
use Sarala\Http\Middleware\ContentNegotiation;
use Orchestra\Testbench\TestCase as BaseTestCase;
use Illuminate\Routing\Middleware\SubstituteBindings;

class TestCase extends BaseTestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->loadMigrationsFrom(__DIR__.'/database/migrations');
        $this->artisan('migrate', ['--database' => 'testbench']);
        $this->withFactories(__DIR__.'/database/factories');
        $this->registerRoutes();
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('sarala.base_url', 'http://localhost');
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
            ->middleware([SubstituteBindings::class, ContentNegotiation::class])
            ->group(function () {
                require __DIR__.'/routes.php';
            });
    }

    public function apiRequest($method, $uri, array $data = [])
    {
        return $this->json($method, $uri, $data, [
            'CONTENT_TYPE' => 'application/vnd.api+json',
            'Accept' => 'application/vnd.api+json',
        ]);
    }
}
