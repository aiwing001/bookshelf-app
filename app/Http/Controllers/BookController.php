<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Genre;
use Illuminate\Http\Request;
use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use Illuminate\Support\Facades\Http;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $query = Book::withAvg('reviews', 'rating');

        if ($request->filled('keyword')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->keyword . '%')
                    ->orWhere('author', 'like', '%' . $request->keyword . '%');
            });
        }

        if ($request->filled('genre')) {
            $query->whereHas('genres', function ($q) use ($request) {
                $q->where('genres.id', $request->genre);
            });
        }

        if ($request->filled('sort')) {
            switch ($request->sort) {
                case 'latest':
                    $query->latest();
                    break;

                case 'oldest':
                    $query->oldest();
                    break;

                case 'title':
                    $query->orderBy('title');
                    break;

                case 'rating':
                    $query->orderByDesc('reviews_avg_rating');
                    break;

                default:
                    $query->latest();
            }
        } else {
            $query->latest();
        }

        $books = $query->paginate(10);

        $genres = Genre::all();

        return view('books.index', compact('books', 'genres'));
    }

    public function create()
    {
        $genres = Genre::all();

        return view('books.create', compact('genres'));
    }

    public function store(StoreBookRequest $request)
    {
        $data = $request->validated();

        $genreIds = $data['genres'];
        unset($data['genres']);

        $data['user_id'] = auth()->id();

        $book = Book::create($data);
        $book->genres()->sync($genreIds);

        return redirect()
            ->route('books.index')
            ->with('success', '書籍を登録しました');
    }

    public function show(Book $book)
    {
        return view('books.show', compact('book'));
    }

    public function edit(Book $book)
    {
        $this->authorize('update', $book);

        $genres = Genre::all();

        return view('books.edit', compact('book', 'genres'));
    }

    public function update(UpdateBookRequest $request, Book $book)
    {
        $this->authorize('update', $book);

        $data = $request->validated();

        $genreIds = $data['genres'];
        unset($data['genres']);

        $book->update($data);

        $book->genres()->sync($genreIds);

        return redirect()
            ->route('books.index')
            ->with('success', '書籍を更新しました');
    }

    public function destroy(Book $book)
    {
        $this->authorize('delete', $book);

        $book->delete();

        return redirect()
            ->route('books.index')
            ->with('success', '書籍を削除しました');
    }

    public function searchByIsbn(string $isbn)
    {
        $response = Http::get(
            'https://www.googleapis.com/books/v1/volumes',
            [
                'q' => 'isbn:' . $isbn,
                'key' => config('services.google_books.key'),
            ]
        );

        $data = $response->json();

        if (! isset($data['items'][0]['volumeInfo'])) {
            return response()->json([
                'error' => '書籍情報を取得できませんでした'
            ], 404);
        }

        $volumeInfo = $data['items'][0]['volumeInfo'];

        return response()->json([
            'title' => $volumeInfo['title'],
            'author' => implode('・', $volumeInfo['authors']),
            'published_date' => $volumeInfo['publishedDate'],
            'image_url' => $volumeInfo['imageLinks']['thumbnail'] ?? null,
            'description' => $volumeInfo['description'] ?? '',
        ]);
    }
}
