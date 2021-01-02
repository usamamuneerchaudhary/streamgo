<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthorControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function returns_authors_view()
    {
        $response = $this->get(route('authors'));
        $response->assertStatus(200);
        $response->assertViewIs('authors.index');
    }

    /**
     * @test
     */
    public function pages_has_authors()
    {
        $author = \App\Models\User::factory()->create();
        $response = $this->get(route('authors'));
        $response->assertStatus(200);
        $response->assertSee($author->name);
    }

    /**
     * @test
     */
    public function author_has_messages()
    {
        $author = \App\Models\User::factory()->create();
        $message = \App\Models\Message::factory()->create(['author_id' => $author->id]);

        $response = $this->get(route('authors'));
        $response->assertStatus(200);
        $response->assertSee($message->body);
    }
}
