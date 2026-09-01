<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Models\Book;
use App\Models\Genre;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;

class BookController extends Controller
{
    public function index(Request $request): View
    {
        $query = Book::with('genres')
            ->withAvg('reviews', 'rating');

        if ($request->filled('keyword')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%'.$request->keyword.'%')
                    ->orWhere('author', 'like', '%'.$request->keyword.'%');
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
                case 'newest':
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

        $books = $query->paginate(10)->withQueryString();

        $genres = Genre::all();

        return view('books.index', compact('books', 'genres'));
    }

    public function create(): View
    {
        $genres = Genre::all();

        return view('books.create', compact('genres'));
    }

    /**
     * 書籍を登録し、ジャンルとの関連付けを行う。
     */
    public function store(StoreBookRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $genreIds = $data['genres'];
        unset($data['genres']);

        $data['user_id'] = auth()->id();

        DB::transaction(function () use ($data, $genreIds) {
            $book = Book::create($data);

            $book->genres()->sync($genreIds);
        });

        return redirect()
            ->route('books.index')
            ->with('success', '書籍を登録しました');
    }

    public function show(Book $book): View
    {
        $book->load([
            'genres',
            'reviews.user',
            'reviews.likedByUsers',
        ]);

        return view('books.show', compact('book'));
    }

    public function edit(Book $book): View
    {
        $this->authorize('update', $book);

        $genres = Genre::all();

        return view('books.edit', compact('book', 'genres'));
    }

    /**
     * 書籍情報を更新し、ジャンルとの関連付けを更新する。
     */
    public function update(
        UpdateBookRequest $request,
        Book $book
    ): RedirectResponse {
        $this->authorize('update', $book);

        $data = $request->validated();

        $genreIds = $data['genres'];
        unset($data['genres']);

        DB::transaction(function () use ($book, $data, $genreIds) {
            $book->update($data);

            $book->genres()->sync($genreIds);
        });

        return redirect()
            ->route('books.index')
            ->with('success', '書籍を更新しました');
    }

    public function destroy(Book $book): RedirectResponse
    {
        $this->authorize('delete', $book);

        $book->delete();

        return redirect()
            ->route('books.index')
            ->with('success', '書籍を削除しました');
    }

    /**
     * ISBNをもとにGoogle Books APIから書籍情報を取得する。
     */
    public function searchByIsbn(string $isbn): JsonResponse
    {
        $response = Http::get(
            'https://www.googleapis.com/books/v1/volumes',
            [
                'q' => 'isbn:'.$isbn,
                'key' => config('services.google_books.key'),
            ]
        );

        if ($response->failed()) {
            return response()->json([
                'error' => '書籍情報の取得中にエラーが発生しました',
            ], $response->status());
        }

        $data = $response->json();

        if (! isset($data['items'][0]['volumeInfo'])) {
            return response()->json([
                'error' => '該当するISBNの書籍が見つかりませんでした',
            ], 404);
        }

        $volumeInfo = $data['items'][0]['volumeInfo'];

        return response()->json([
            'title' => $volumeInfo['title'] ?? '',
            'author' => isset($volumeInfo['authors'])
                ? implode('・', $volumeInfo['authors'])
                : '',
            'published_date' => $volumeInfo['publishedDate'] ?? '',
            'image_url' => $volumeInfo['imageLinks']['thumbnail'] ?? null,
            'description' => $volumeInfo['description'] ?? '',
        ]);
    }
}
