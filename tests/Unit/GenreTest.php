<?php

namespace Tests\Unit;

use App\Models\Genre;
use Tests\TestCase;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class GenreTest extends TestCase
{
    public function test_genre_has_many_books(): void
    {
        $genre = new Genre();

        $this->assertInstanceOf(
            BelongsToMany::class,
            $genre->books()
        );
    }
}
