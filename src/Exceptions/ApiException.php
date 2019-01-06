<?php

declare(strict_types=1);

namespace Sarala\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

abstract class ApiException extends Exception implements JsonApiExceptionContract
{
    /**
     * Get unique identifier for this particular occurrence
     * of the problem.
     */
    public function id(): ?string
    {
        return null;
    }

    /**
     * Get application-specific error code.
     */
    public function code(): ?string
    {
        return null;
    }

    /**
     * Get human-readable explanation specific to this
     * occurrence of the problem.
     */
    public function detail(): ?string
    {
        return null;
    }

    /**
     * Get the URI that yield further details about this
     * particular occurrence of the problem.
     */
    public function href(): ?string
    {
        return null;
    }

    /**
     * Get associated resources, which can be dereferenced
     * from the request document.
     */
    public function links(): array
    {
        return [];
    }

    /**
     * Get relative path to the relevant attribute within
     * the associated resource(s).
     */
    public function path(): ?string
    {
        return null;
    }

    public function render(Request $request): JsonResponse
    {
        $data = [
            'status' => (string) $this->status(),
            'title' => (string) $this->title(),
        ];

        if (! empty($this->links())) {
            $data['links'] = $this->links();
        }

        $data = array_merge($data, $this->getAvailableData());

        return response()
            ->json(['error' => $data], $this->status(), ['Content-Type' => 'application/vnd.api+json']);
    }

    private function getAvailableData(): array
    {
        $data = [];

        collect(['id', 'href', 'code', 'detail', 'path'])->each(function ($key) use (&$data) {
            if (! is_null($this->{$key}())) {
                $data[$key] = $this->{$key}();
            }
        });

        return $data;
    }
}
