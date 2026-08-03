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
            'published_date' => 'required|date',
            'image_url' => 'nullable|url|max:255',
            // 'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
            'description' => 'required',
            'genres' => 'required|array',
            'genres.*' => 'exists:genres,id',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'タイトルを入力してください',
            'title.max' => 'タイトルは255文字以内で入力してください',

            'author.required' => '著者を入力してください',
            'author.max' => '著者は255文字以内で入力してください',

            'isbn.required' => 'ISBNを入力してください',
            'isbn.unique' => 'このISBNはすでに登録されています',

            'published_date.required' => '出版日を入力してください。',
            'published_date.date' => '出版日は日付形式で入力してください',

            'image_url.url' => '画像URLは正しいURL形式で入力してください',
            'image_url.max' => '画像URLは255文字以内で入力してください',

            'description.required' => '説明を入力してください',

            'genres.required' => 'ジャンルを1つ以上選択してください',
            'genres.array' => 'ジャンルの形式が正しくありません',
            'genres.*.exists' => '選択されたジャンルが存在しません',
        ];
    }
}
