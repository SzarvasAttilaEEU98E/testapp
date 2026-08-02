<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SearchBookRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['nullable', 'string', 'max:80'],
            'author' => ['nullable', 'string', 'max:80'],
            'minRating' => ['nullable', 'numeric', 'between:1,5'],
            'sortBy' => ['nullable', 'string', 'in:title,author,publishedYear,averageRating'],
            'order' => ['nullable', 'string', 'in:asc,desc'],
        ];
    }
}