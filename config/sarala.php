<?php

return [
    /*
     * League\Fractal\Serializer\JsonApiSerializer will use this value to
     * as a prefix for generated links. Set to `null` to disable this.
     */
    'base_url' => env('API_URL', '/api'),

    'guard' => null,

    'response_headers' => [
        'Content-Type' => 'application/vnd.api+json',
    ]
];
