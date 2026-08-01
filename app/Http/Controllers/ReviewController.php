<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReviewRequest;
use App\Http\Requests\UpdateReviewRequest;
use App\Models\Book;
use App\Models\Review;

class ReviewController extends Controller
{
    public function index()
    {
        $reviews = Review::paginate(10);

        return view('reviews.index', compact('reviews'));
    }

    public function create()
    {
        return view('reviews.create');
    }

    public function store(StoreReviewRequest $request, Book $book)
    {
        $data = $request->validated();

        $data['user_id'] = auth()->id();
        $data['book_id'] = $book->id;

        Review::create($data);

        return redirect()->route('books.show', $book);
    }

    public function show(Review $review)
    {
        return view('reviews.show', compact('review'));
    }

    public function edit(Review $review)
    {
        return view('reviews.edit', compact('review'));
    }

    public function update(UpdateReviewRequest $request, Review $review)
    {
        $review->update($request->validated());

        return redirect()->route('books.show', $review->book);
    }

    public function destroy(Review $review)
    {
        $book = $review->book;

        $review->delete();

        return redirect()->route('books.show', $book);
    }
}
