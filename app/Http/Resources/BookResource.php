<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'author' => $this->author,
            'isbn' => $this->isbn,
            'published_date' => $this->published_date,
            'image_url' => $this->image_url,
            'description' => $this->description,

            'genres' => $this->whenLoaded('genres', function () {
                return $this->genres->map(function ($genre) {
                    return [
                        'id' => $genre->id,
                        'name' => $genre->name,
                    ];
                });
            }),

            'reviews_avg_rating' => $this->when(
                isset($this->reviews_avg_rating),
                $this->reviews_avg_rating
            ),

            'reviews_count' => $this->when(
                isset($this->reviews_count),
                $this->reviews_count
            ),

            'reviews' => $this->whenLoaded('reviews', function () {
                return $this->reviews->map(function ($review) {
                    return [
                        'id' => $review->id,
                        'rating' => $review->rating,
                        'comment' => $review->comment,
                        'created_at' => $review->created_at,
                        'user' => [
                            'id' => $review->user->id,
                            'name' => $review->user->name,
                        ],
                    ];
                });
            }),
        ];
    }
}
