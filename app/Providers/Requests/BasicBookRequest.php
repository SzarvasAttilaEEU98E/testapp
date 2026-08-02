<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BasicBookRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $isbnRule = Rule::unique('books', 'isbn');
        if ($this->route('id')){
            $isbnRule->ignore($this->route('id'));
        }
        return [
            'title' => ['required', 'string', 'max:80'],
            'author' => ['required', 'string', 'max:80'],
            'isbn' => ['required', 'string', $isbnRule],
            'publishedYear' => ['required', 'integer', 'digits:4'],
            'available' => ['boolean'],
        ];
    }
}