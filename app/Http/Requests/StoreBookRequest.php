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
            'title' => 'required|max:255',
            'author' => 'required|max:255',
            'isbn' => 'required|unique:books,isbn',
            'published_date' => 'nullable|date',
            'image_url' => 'nullable|url|max:255',
            // 'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
            'description' => 'required',
            'genres' => 'required|array',
            'genres.*' => 'exists:genres,id',
        ];
    }
}
