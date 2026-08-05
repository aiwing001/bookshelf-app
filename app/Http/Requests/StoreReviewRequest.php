<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Review;

class StoreReviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'rating.required' => '評価を選択してください',
            'comment.required' => 'レビューを入力してください',
        ];
    }

    public function after(): array
    {
        $book = $this->route('book');

        $exists = Review::where('user_id', auth()->id())
            ->where('book_id', $book->id)
            ->exists();

        return [
            function ($validator) use ($exists) {
                if ($exists) {
                    $validator->errors()->add(
                        'comment',
                        'この書籍にはすでにレビューを投稿しています'
                    );
                }
            },
        ];
    }
}
