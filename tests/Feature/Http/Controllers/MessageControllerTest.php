<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MessageControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function returns_message_create_form()
    {
        $response = $this->get(route('message.create'));
        $response->assertStatus(200);
        $response->assertViewIs('message.create');
    }

    /**
     * @test
     */
    public function see_authors_lists_on_create_form()
    {
        $author = \App\Models\User::factory()->create();
        $response = $this->get(route('message.create'));
        $response->assertStatus(200);
        $response->assertViewIs('message.create');
        $response->assertSee($author->name);
    }

    /**
     * @test
     */
    public function new_message_shoudl_be_associate_to_an_author()
    {
        $author = \App\Models\User::factory()->create();
        $message = \App\Models\Message::factory()->create([
            'author_id' => $author->id
        ]);
        $response = $this->post('api/create-message', [
            'body' => $message->body,
            'author_id' => $author->id
        ]);
        $response->assertStatus(200);
        $response->assertJson([
            'error' => false,
            'message' => 'Message Created successfully'
        ]);
    }

    /**
     * @test
     */
    public function new_message_cannot_be_created_without_author()
    {
        $author = \App\Models\User::factory()->create();
        $message = \App\Models\Message::factory()->create([
            'author_id' => $author->id
        ]);
        $response = $this->post('api/create-message', [
            'body' => $message->body,
        ]);
        $response->assertStatus(400);
        $response->assertJson([
            'error' => true,
        ]);
    }

    /**
     * @test
     */
    public function new_message_cannot_be_Created_without_body()
    {
        $author = \App\Models\User::factory()->create();
        $message = \App\Models\Message::factory()->create([
            'author_id' => $author->id
        ]);
        $response = $this->post('api/create-message', [
            'author_id' => $author->id
        ]);
        $response->assertStatus(400);
    }


}
