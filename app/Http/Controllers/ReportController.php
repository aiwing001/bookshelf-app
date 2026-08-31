<?php

namespace App\Http\Controllers;

class ReportController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $user->load('reviews.book.genres');

        $distribution = $user->reviews
            ->groupBy('rating')
            ->map(fn ($reviews) => $reviews->count());

        $ratingDistribution = collect(range(1, 5))
            ->map(function ($rating) use ($distribution) {
                return $distribution->get($rating, 0);
            });

        $topRatedReviews = $user->reviews()
            ->with('book')
            ->where('rating', '>=', 4)
            ->orderByDesc('rating')
            ->take(5)
            ->get();

        $topRatedBooks = $topRatedReviews->map(function ($review) {
            return [
                'id' => $review->book->id,
                'title' => $review->book->title,
                'author' => $review->book->author,
                'rating' => $review->rating,
            ];
        });

        $genreRatings = collect();

        foreach ($user->reviews as $review) {
            foreach ($review->book->genres as $genre) {
                $genreRatings->push([
                    'id' => $genre->id,
                    'name' => $genre->name,
                    'rating' => $review->rating,
                ]);
            }
        }

        $genreRatings = $genreRatings
            ->groupBy('id')
            ->map(function ($reviews) {
                return [
                    'id' => $reviews->first()['id'],
                    'name' => $reviews->first()['name'],
                    'count' => $reviews->count(),
                    'average_rating' => $reviews->avg('rating'),
                ];
            })
            ->sortByDesc('average_rating')
            ->take(5)
            ->values();

        $stats = [
            'summary' => [
                'total_reviews' => $user->reviews()->count(),

                'books_read' => $user->reviews()
                    ->distinct('book_id')
                    ->count('book_id'),

                'average_rating' => $user->reviews()
                    ->avg('rating') ?? 0,
            ],

            'rating_distribution' => $ratingDistribution,

            'top_rated_books' => $topRatedBooks,

            'genre_ratings' => $genreRatings,
        ];

        return view('reports.index', compact('stats'));
    }
}
