<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\IndexBookRequest;
use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Http\Resources\BookResource;
use App\Models\Book;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;

class BookController extends Controller
{
    public function index(IndexBookRequest $request): AnonymousResourceCollection
    {
        $validated = $request->validated();

        $query = Book::with('genres')
            ->withAvg('reviews', 'rating')
            ->withCount('reviews');

        if (! empty($validated['keyword'])) {
            $query->where(function ($query) use ($validated) {
                $query->where('title', 'like', '%'.$validated['keyword'].'%')
                    ->orWhere('author', 'like', '%'.$validated['keyword'].'%');
            });
        }

        if (! empty($validated['genre'])) {
            $query->whereHas('genres', function ($query) use ($validated) {
                $query->where('genres.id', $validated['genre']);
            });
        }

        $perPage = $validated['per_page'] ?? 10;

        $books = $query->paginate($perPage);

        return BookResource::collection($books);
    }

    public function show(Book $book): BookResource
    {
        $book->load([
            'genres',
            'reviews.user',
        ]);

        return new BookResource($book);
    }

    public function store(StoreBookRequest $request)
    {
        $data = $request->validated();

        $genreIds = $data['genres'];
        unset($data['genres']);

        $data['user_id'] = auth()->id();

        $book = DB::transaction(function () use ($data, $genreIds) {
            $book = Book::create($data);

            $book->genres()->sync($genreIds);

            return $book;
        });

        $book->load('genres');

        return (new BookResource($book))
            ->response()
            ->setStatusCode(201);
    }

    public function update(
        UpdateBookRequest $request,
        Book $book
    ): BookResource {
        $this->authorize('update', $book);

        $data = $request->validated();

        $genreIds = $data['genres'];
        unset($data['genres']);

        DB::transaction(function () use ($book, $data, $genreIds) {
            $book->update($data);

            $book->genres()->sync($genreIds);
        });

        $book->load('genres');

        return new BookResource($book);
    }

    public function destroy(Book $book): JsonResponse
    {
        $this->authorize('delete', $book);

        $book->delete();

        return response()->json(null, 204);
    }
}
