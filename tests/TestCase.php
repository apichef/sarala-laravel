<?php

declare(strict_types=1);

namespace Sarala;

use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Support\Facades\Route;
use Orchestra\Testbench\TestCase as BaseTestCase;
use Sarala\Http\Middleware\ContentNegotiation;
use Sarala\Http\Middleware\ETag;

class TestCase extends BaseTestCase
{
    protected function setUp(): void
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
        $app['config']->set('sarala.handlers', [
            'json' => [
                'media_type' => 'application/json',
                'serializer' => \League\Fractal\Serializer\DataArraySerializer::class,
            ],
            'json_api' => [
                'media_type' => 'application/vnd.api+json',
                'serializer' => \League\Fractal\Serializer\JsonApiSerializer::class,
            ],
        ]);

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
            SaralaServiceProvider::class,
        ];
    }

    protected function registerRoutes(): void
    {
        Route::namespace('Sarala\Dummy\Http\Controllers')
            ->middleware([SubstituteBindings::class, ContentNegotiation::class, ETag::class])
            ->group(function () {
                require __DIR__.'/routes.php';
            });
    }

    public function withJsonApiHeaders($method, $uri, array $data = [], $headers = [])
    {
        return $this->json($method, $uri, $data, array_merge([
            'CONTENT_TYPE' => 'application/vnd.api+json',
            'Accept' => 'application/vnd.api+json',
        ], $headers));
    }
}
