<?php

declare(strict_types=1);

namespace Sarala\Http\Requests;

use Sarala\Query\QueryBuilderAbstract;
use Illuminate\Foundation\Http\FormRequest;

abstract class ApiRequestAbstract extends FormRequest
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

    public function builder(): ?QueryBuilderAbstract
    {
        return null;
    }
}
