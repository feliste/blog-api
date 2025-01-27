<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Author;
use App\Models\Post;
use App\Models\Comment;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Seed authors
        $authors = Author::factory(1000)->make()->toArray();  
        DB::table('authors')->insert($authors);  

        // Create posts
        $posts = [];
        $authors = Author::all(); 

        foreach ($authors as $author) {
            $numPosts = rand(100, 500);  

            foreach (range(1, $numPosts) as $index) {
                $posts[] = [
                    'author_id' => $author->id,
                    'title' => "Post Title {$author->id}-{$index}",
                    'body' => 'Post body content here.',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        DB::table('posts')->insert($posts);  

        // Create comments
        $comments = [];
        $posts = Post::all();  

        foreach ($posts as $post) {
            $numComments = rand(1, 50);  

            foreach (range(1, $numComments) as $index) {
                $comments[] = [
                    'post_id' => $post->id,
                    'author_id' => $authors->random()->id,
                    'name' => "Comment Author {$index}",
                    'text' => 'Comment text here.',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        DB::table('comments')->insert($comments);  
    }
}
