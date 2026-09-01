<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class IndexBookRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'keyword' => ['nullable', 'string', 'max:255'],
            'genre' => ['nullable', 'integer', 'exists:genres,id'],
            'page' => ['nullable', 'integer', 'min:1'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
        ];
    }

    public function messages(): array
    {
        return [
            'keyword.string' => 'キーワードは文字列で入力してください',
            'keyword.max' => 'キーワードは255文字以内で入力してください',

            'genre.integer' => 'ジャンルIDは整数で入力してください',
            'genre.exists' => '指定されたジャンルが存在しません',

            'page.integer' => 'ページ番号は整数で入力してください',
            'page.min' => 'ページ番号は1以上で指定してください',

            'per_page.integer' => '1ページあたりの件数は整数で入力してください',
            'per_page.min' => '1ページあたりの件数は1件以上で指定してください',
            'per_page.max' => '1ページあたりの件数は100件以内で指定してください',
        ];
    }
}
