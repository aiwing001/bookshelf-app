<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Genre;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GenreTest extends TestCase
{
    use RefreshDatabase;

    // ===== 画面アクセス =====
    public function test_genre_index_is_displayed(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('genres.index'));

        $response->assertOk();
    }

    public function test_genre_create_is_displayed(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('genres.create'));

        $response->assertOk();
    }

    public function test_genre_show_is_displayed(): void
    {
        $user = User::factory()->create();
        $genre = Genre::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('genres.show', $genre));

        $response->assertOk();
    }

    public function test_genre_edit_is_displayed(): void
    {
        $user = User::factory()->create();
        $genre = Genre::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('genres.edit', $genre));

        $response->assertOk();
    }

    // ===== CRUD =====
    public function test_genre_can_be_stored(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->post(route('genres.store'), [
                'name' => '小説',
            ]);

        $response->assertRedirect(route('genres.index'));

        $this->assertDatabaseHas('genres', [
            'name' => '小説',
        ]);
    }

    public function test_genre_can_be_updated(): void
    {
        $user = User::factory()->create();
        $genre = Genre::factory()->create([
            'name' => '文学',
        ]);

        $response = $this->actingAs($user)
            ->put(route('genres.update', $genre), [
                'name' => '小説',
            ]);

        $response->assertRedirect(route('genres.index'));

        $this->assertDatabaseHas('genres', [
            'id' => $genre->id,
            'name' => '小説',
        ]);
    }

    public function test_genre_can_be_deleted(): void
    {
        $user = User::factory()->create();
        $genre = Genre::factory()->create();

        $response = $this->actingAs($user)
            ->delete(route('genres.destroy', $genre));

        $response->assertRedirect(route('genres.index'));

        $this->assertDatabaseMissing('genres', [
            'id' => $genre->id,
        ]);
    }

    // ===== バリデーション =====
    public function test_genre_name_is_required(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->post(route('genres.store'), [
                'name' => '',
            ]);

        $response->assertSessionHasErrors('name');
    }

    public function test_genre_name_must_be_unique(): void
    {
        $user = User::factory()->create();

        Genre::factory()->create([
            'name' => '小説',
        ]);

        $response = $this->actingAs($user)
            ->post(route('genres.store'), [
                'name' => '小説',
            ]);

        $response->assertSessionHasErrors('name');
    }

    public function test_genre_can_be_updated_with_same_name(): void
    {
        $user = User::factory()->create();

        $genre = Genre::factory()->create([
            'name' => '小説',
        ]);

        $response = $this->actingAs($user)
            ->put(route('genres.update', $genre), [
                'name' => '小説',
            ]);

        $response->assertSessionDoesntHaveErrors('name');
    }

    public function test_genre_cannot_be_updated_with_duplicate_name(): void
    {
        $user = User::factory()->create();

        $genre = Genre::factory()->create([
            'name' => '小説',
        ]);

        Genre::factory()->create([
            'name' => '文学',
        ]);

        $response = $this->actingAs($user)
            ->put(route('genres.update', $genre), [
                'name' => '文学',
            ]);

        $response->assertSessionHasErrors('name');
    }

    // ===== 削除制御 =====
    public function test_genre_used_by_book_cannot_be_deleted(): void
    {
        $user = User::factory()->create();
        $genre = Genre::factory()->create();
        $book = Book::factory()->create();

        $book->genres()->attach($genre->id);

        $response = $this->actingAs($user)
            ->delete(route('genres.destroy', $genre));

        $response->assertRedirect(route('genres.index'));

        $this->assertDatabaseHas('genres', [
            'id' => $genre->id,
        ]);
    }
}
