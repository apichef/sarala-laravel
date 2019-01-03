<?php

declare(strict_types=1);

namespace Sarala\Exceptions;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

abstract class ApiException extends Exception implements JsonApiExceptionContract
{
    public function render(Request $request): Response
    {
        $data = [
            'status' => (string) $this->status(),
            'title' => (string) $this->title(),
        ];

        if (! empty($this->links())) {
            $data['links'] = $this->links();
        }

        $data = array_merge($data, $this->getAvailableData());

        return response($data, $this->status());
    }

    private function getAvailableData(): array
    {
        $data = [];

        collect(['id', 'href', 'code', 'detail', 'path'])->each(function ($key) {
            if (! is_null($this->{$key}())) {
                $data[$key] = $this->{$key}();
            }
        });

        return $data;
    }
}
