<?php

namespace Database\Factories;

use App\Models\Comment;
use App\Models\Post;
use App\Models\Author;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommentFactory extends Factory
{
    protected $model = Comment::class;

    public function definition()
    {
        return [
            'post_id' => Post::factory(),
            'author_id' => Author::factory(),
            'name' => $this->faker->name(),
            'text' => $this->faker->sentence(),
        ];
    }
}
