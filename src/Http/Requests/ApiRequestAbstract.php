<?php

declare(strict_types=1);

namespace Sarala\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Sarala\Contracts\ApiRequestContract;

abstract class ApiRequestAbstract extends FormRequest implements ApiRequestContract
{
    public function authorize(): bool
    {
        return false;
    }

    public function rules(): array
    {
        return [];
    }

    public function allowedIncludes(): array
    {
        return [];
    }
}
