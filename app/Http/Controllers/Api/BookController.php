<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Models\Book;

class BookController extends Controller
{
    public function index()
    {
        $books = Book::with('genres')
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->get();

        return response()->json($books);
    }

    public function show(Book $book)
    {
        $book->load([
            'genres',
            'reviews.user',
        ]);

        return response()->json($book);
    }

    public function store(StoreBookRequest $request)
    {
        $data = $request->validated();

        $genreIds = $data['genres'];
        unset($data['genres']);

        $data['user_id'] = auth()->id();

        $book = Book::create($data);

        $book->genres()->sync($genreIds);

        $book->load('genres');

        return response()->json($book, 201);
    }

    public function update(UpdateBookRequest $request, Book $book)
    {
        $this->authorize('update', $book);

        $data = $request->validated();

        $genreIds = $data['genres'];
        unset($data['genres']);

        $book->update($data);

        $book->genres()->sync($genreIds);

        $book->load('genres');

        return response()->json($book);
    }

    public function destroy(Book $book)
    {
        $this->authorize('delete', $book);

        $book->delete();

        return response()->json(null, 204);
    }
}
