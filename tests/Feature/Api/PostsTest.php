<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use App\Models\Author;
use App\Models\Post;
use App\Models\Comment;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PostsTest extends TestCase
{
    use RefreshDatabase;

    public function test_returns_paginated_posts()
    {
        // Create an author, a post, and a comment
        $author = Author::factory()->create();
        $post = Post::factory()->create(['author_id' => $author->id]);
        
        // Create a comment with a different author (ensure comment has a unique author)
        $commentAuthor = Author::factory()->create();
        $comment = Comment::factory()->create([
            'post_id' => $post->id,
            'author_id' => $commentAuthor->id, 
        ]);

        // Make API call to fetch paginated posts
        $response = $this->getJson('/api/posts');

        // Assert the response structure and status
        $response->assertStatus(200)
                 ->assertJson([
                     'data' => [
                         [
                             'title' => $post->title,
                             'author' => $author->name,
                             'comments' => [
                                 [
                                     'name' => $commentAuthor->name, 
                                     'text' => $comment->text,
                                 ],
                             ],
                         ],
                     ],
                     'meta' => [
                         'current_page' => 1,
                         'last_page' => 1,
                         'per_page' => 15,
                         'total' => 1,
                     ],
                 ]);
    }

    public function test_filters_posts_by_author_id()
    {
        $author = Author::factory()->create();
        $post1 = Post::factory()->create(['author_id' => $author->id]);
        $post2 = Post::factory()->create();

        // Fetch posts filtered by the author's ID
        $response = $this->getJson('/api/posts?author_id=' . $author->id);

        // Assert only the first post is returned
        $response->assertStatus(200)
                 ->assertJson([
                     'data' => [
                         [
                             'title' => $post1->title,
                             'author' => $author->name,
                             'comments' => [],
                         ],
                     ],
                     'meta' => [
                         'current_page' => 1,
                         'last_page' => 1,
                         'per_page' => 15,
                         'total' => 1,
                     ],
                 ])
                 ->assertJsonMissing(['title' => $post2->title]);
    }

    public function test_filters_posts_by_title()
    {
        // Create two posts with distinct titles
        $post1 = Post::factory()->create(['title' => 'Sample Post']);
        $post2 = Post::factory()->create(['title' => 'Another Post']);

        // Fetch posts filtered by title substring
        $response = $this->getJson('/api/posts?title=sample');

        $response->assertStatus(200)
                 ->assertJson([
                     'data' => [
                         [
                             'title' => $post1->title,
                             'author' => $post1->author->name,
                             'comments' => [],
                         ],
                     ],
                     'meta' => [
                         'current_page' => 1,
                         'last_page' => 1,
                         'per_page' => 15,
                         'total' => 1,
                     ],
                 ])
                 ->assertJsonMissing(['title' => $post2->title]);
    }

    public function test_returns_validation_error_for_invalid_parameters()
    {
        // Test with an invalid author_id that doesn't exist
        $response = $this->getJson('/api/posts?author_id=999999');

        // Assert the validation error response
        $response->assertStatus(422)
                 ->assertJson([
                     'message' => 'The selected author id is invalid.', 
                     'errors' => [
                         'author_id' => [
                             'The selected author id is invalid.',
                         ],
                     ],
                 ]);
    }
}
