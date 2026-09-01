<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookRequest extends FormRequest
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
            'name' => 'required|max:255',
            'author' => 'required|max:255',
            'publication' => 'required|date',
            'image_url' => 'nullable|url|max:255',
            // 'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
            'description' => 'required',
            'isbn' => 'required|unique:books,isbn',
            'genre_ids' => 'required|array',
            'genre_ids.*' => 'exists:genres,id',
        ];
    }
}
