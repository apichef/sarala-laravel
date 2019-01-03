<?php

declare(strict_types=1);

namespace Sarala\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Sarala\FetchQueryBuilderAbstract;

abstract class ApiRequestAbstract extends FormRequest
{
    abstract public function builder(): FetchQueryBuilderAbstract;

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
