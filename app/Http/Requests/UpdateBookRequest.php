<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBookRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|max:255',
            'author' => 'required|max:255',
            'published_date' => 'required|date',
            'image_url' => 'nullable|url|max:255',
            'description' => 'nullable',

            'isbn' => [
                'required',
                Rule::unique('books', 'isbn')->ignore($this->route('book')),
            ],

            'genres' => 'required|array',
            'genres.*' => 'exists:genres,id',
        ];
    }
}
