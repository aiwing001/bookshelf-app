<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReviewRequest;
use App\Http\Requests\UpdateReviewRequest;
use App\Models\Book;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ReviewController extends Controller
{
    public function index(): View
    {
        $reviews = Review::paginate(10);

        return view('reviews.index', compact('reviews'));
    }

    public function create(): View
    {
        return view('reviews.create');
    }

    public function store(StoreReviewRequest $request, Book $book): RedirectResponse
    {
        $data = $request->validated();

        $data['user_id'] = auth()->id();
        $data['book_id'] = $book->id;

        Review::create($data);

        return redirect()
            ->route('books.show', $book)
            ->with('success', 'レビューを登録しました');
    }

    public function show(Review $review): View
    {
        return view('reviews.show', compact('review'));
    }

    public function edit(Review $review): View
    {
        $this->authorize('update', $review);

        return view('reviews.edit', compact('review'));
    }

    public function update(UpdateReviewRequest $request, Review $review): RedirectResponse
    {
        $this->authorize('update', $review);

        $review->update($request->validated());

        return redirect()
            ->route('books.show', $review->book)
            ->with('success', 'レビューを更新しました');
    }

    public function destroy(Review $review): RedirectResponse
    {
        $this->authorize('delete', $review);

        $book = $review->book;

        $review->delete();

        return redirect()
            ->route('books.show', $book)
            ->with('success', 'レビューを削除しました');
    }

    public function like(Review $review): RedirectResponse
    {
        auth()->user()->likedReviews()->toggle($review->id);

        return back();
    }
}
