<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = ['text', 'post_id', 'author_id'];

    // Relationship with the Author model
    public function author()
    {
        return $this->belongsTo(Author::class);
    }

    // Relationship with the Post model
    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
