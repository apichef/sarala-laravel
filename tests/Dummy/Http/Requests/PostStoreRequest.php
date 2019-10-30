<?php

declare(strict_types=1);

namespace Sarala\Dummy\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostStoreRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title' => 'required',
            'subtitle' => 'required',
            'body' => 'required',
            'published_at' => 'date',
        ];
    }
}
