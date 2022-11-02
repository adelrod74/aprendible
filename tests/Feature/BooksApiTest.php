<?php

namespace Tests\Feature;

use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BooksApiTest extends TestCase
{
    use RefreshDatabase;
    
    /** @test **/
    public function can_get_all_books()
    {
        $books = Book::factory(4)->create();

        $response = $this->getJson(route('books.index'));

        $response->assertJsonFragment([
            'title' => $books[0]->title
        ]);
    }

    /** @test **/
    public function can_get_one_book()
    {
        $book = Book::factory()->create();
        $response = $this->getJson(route('books.show', $book));

        $response->assertJsonFragment([
            'title' => $book->title
        ]);
    }

    /** @test **/
    public function can_create_book()
    {
        $this->postJson(route('books.store'), [])
            ->assertJsonValidationErrorFor('title');

        $this->postJson(route('books.store'), [
            'title' => 'Prueba'
        ])->assertJsonFragment([
            'title' => 'Prueba'
        ]);

        $this->assertDatabaseHas('books', [
            'title' => 'Prueba'
        ]);
    }

    /** @test **/
    public function can_update_book()
    {
        $book = Book::factory()->create();

        $this->patchJson(route('books.update', $book), [])
            ->assertJsonValidationErrorFor('title');

        $this->patchJson(route('books.update', $book), [
            'title' => 'Editado'
        ])->assertJsonFragment([
            'title' => 'Editado'
        ]);

        $this->assertDatabaseHas('books', [
            'title' => 'Editado'
        ]);
    }

    /** @test **/
    public function can_delete_books()
    {
        $book = Book::factory()->create();

        $this->deleteJson(route('books.destroy', $book))
            ->assertNoContent();

        $this->assertDatabaseCount('books', 0);
    }

}