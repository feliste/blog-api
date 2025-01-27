<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Display a listing of comments.
     */
    public function index(Request $request)
    {
        $request->validate([
            'per_page' => 'nullable|integer|max:100', // Limit per_page to a max of 100
        ]);

        $query = Comment::with('post'); // Eager load the post

        // Apply filtering
        if ($request->has('post_id')) {
            $query->where('post_id', $request->post_id);
        }

        // Paginate the results
        $comments = $query->paginate($request->get('per_page', 15)); // Default to 15 per page

        // Custom response with meta data
        return response()->json([
            'data' => $comments->items(),
            'meta' => [
                'current_page' => $comments->currentPage(),
                'last_page' => $comments->lastPage(),
                'per_page' => $comments->perPage(),
                'total' => $comments->total(),
            ]
        ]);
    }

    /**
     * Store a newly created comment in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'text' => 'required|string',
            'post_id' => 'required|exists:posts,id',
        ]);

        $comment = Comment::create($request->all());
        return response()->json($comment, 201);
    }

    /**
     * Display the specified comment.
     */
    public function show($id)
    {
        $comment = Comment::with('post')->find($id);

        if (!$comment) {
            return response()->json(['message' => 'Comment not found'], 404);
        }

        return response()->json($comment);
    }

    /**
     * Update the specified comment in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'text' => 'required|string',
            'post_id' => 'required|exists:posts,id',
        ]);

        $comment = Comment::findOrFail($id);
        $comment->update($request->all());

        return response()->json($comment);
    }

    /**
     * Remove the specified comment from storage.
     */
    public function destroy($id)
    {
        $comment = Comment::find($id);

        if (!$comment) {
            return response()->json(['message' => 'Comment not found'], 404);
        }

        $comment->delete();
        return response()->json(null, 204);
    }
}
