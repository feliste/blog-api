<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Author;
use App\Models\Post;
use App\Models\Comment;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create 1000 authors
        $authors = Author::factory(1000)->create();

        // For each author, create between 100 and 500 posts
        $authors->each(function ($author) {
            $postCount = rand(100, 500);
            $posts = Post::factory($postCount)->create([
                'author_id' => $author->id, 
            ]);

            // For each post, create between 1 and 50 comments
            $posts->each(function ($post) {
                $commentCount = rand(1, 50);
                Comment::factory($commentCount)->create([
                    'post_id' => $post->id, 
                    'author_id' => Author::factory()->create()->id, 
                ]);
            });
        });
    }
}
