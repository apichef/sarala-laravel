<?php

return [

    /*
    |--------------------------------------------------------------------------
    | API Base URL
    |--------------------------------------------------------------------------
    |
    | League\Fractal\Serializer\JsonApiSerializer will use this value to as a
    | prefix for generated links. Set to `null` to disable this.
    |
    */

    'base_url' => env('API_BASE_URL', '/api'),

    /*
    |--------------------------------------------------------------------------
    | API Request Authentication Guard URL
    |--------------------------------------------------------------------------
    |
    | This guard will be used when fetching the authenticated user to pass to the
    | links method in the transformer.
    |
    */

    'guard' => null,

    /*
    |--------------------------------------------------------------------------
    | Content Negotiation Handlers
    |--------------------------------------------------------------------------
    |
    | Here you may define all of the 'handlers' that your API will support.
    | The media_type setting within each handler is used to validate the
    | Content-Type and Accept headers sent by the client against the supported
    | media-types. Based on the given Accept header, the relevant handler's
    | serializer setting is used to serialize the response.
    |
    */

    'handlers' => [

        'json' => [
            'media_type' => 'application/json',
            'serializer' => \League\Fractal\Serializer\DataArraySerializer::class,
        ],

        'json_api' => [
            'media_type' => 'application/vnd.api+json',
            'serializer' => \League\Fractal\Serializer\JsonApiSerializer::class,
        ],

    ],
];
