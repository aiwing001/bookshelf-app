<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class ReportController extends Controller
{
    /**
     * ログインユーザーのレビュー情報を集計し、
     * 読書レポート画面を表示する。
     */
    public function index(): View
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

        $genreRatings = $user->reviews
            ->flatMap(function ($review) {
                return $review->book->genres->map(function ($genre) use ($review) {
                    return [
                        'id' => $genre->id,
                        'name' => $genre->name,
                        'rating' => $review->rating,
                    ];
                });
            })
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
