<?php

namespace Tests\Feature;

use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookAPiTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function can_get_all_books(){
        $books = Book::factory(4)->create();
        $response = $this->getJson(route('books.index'));
        $response->assertJsonFragment([
            'title'=> $books[0]->title
        ])->assertJsonFragment([
            'title'=> $books[1]->title
        ]);
    }

    /** @test */
    function can_get_one_book(){
        $book = Book::factory()->create();
        $response = $this->getJson(route('books.show', $book))
        ->assertJsonFragment([
            'title'=>$book->title
        ]);
    }

    /** @test */
    function can_create_books(){
        $this->postJson(route('books.store'),[
            'title'=>'Book test'
        ])->assertJsonFragment([
            'title'=>'Book test'
        ]);

        $this->postJson(route('books.store'),[])->assertJsonValidationErrorFor('title');

        $this->assertDatabaseHas('books', [
            'title'=>'Book test'
        ]);
    }

    /** @test */
    function can_update_books(){
        $book = Book::factory()->create();
        $this->patchJson(route('books.update', $book),[])->assertJsonValidationErrorFor('title');
        $this->patchJson(route('books.update', $book),[
            'title'=>'Book test update'
        ])->assertJsonFragment([
            'title'=>'Book test update'
        ]);

        $this->assertDatabaseHas('books', [
            'title'=>'Book test update'
        ]);
    }

    /** @test */
    function can_delete_books(){
        $book = Book::factory()->create();
        $this->deleteJson(route('books.destroy', $book))->assertNoContent();
        $this->assertDatabaseCount('books',0);
    }
}
