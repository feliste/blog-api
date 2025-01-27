<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

/**
 * @OA\Info(
 *     title="Blog API",
 *     version="1.0.0",
 *     description="API documentation for managing blog posts.",
 * )
 */
class PostController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/posts",
     *     summary="Get a list of posts",
     *     description="Retrieve a list of blog posts with optional filtering and pagination.",
     *     tags={"Posts"},
     *     @OA\Parameter(
     *         name="author_id",
     *         in="query",
     *         description="Filter posts by author ID",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="title",
     *         in="query",
     *         description="Search posts by title",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Page number for pagination",
     *         required=false,
     *         @OA\Schema(type="integer", default=1)
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Number of items per page (default: 15)",
     *         required=false,
     *         @OA\Schema(type="integer", default=15)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of posts with pagination metadata.",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="title", type="string"),
     *                     @OA\Property(property="author", type="string"),
     *                     @OA\Property(property="comments", type="array",
     *                         @OA\Items(
     *                             type="object",
     *                             @OA\Property(property="text", type="string"),
     *                             @OA\Property(property="name", type="string")
     *                         )
     *                     )
     *                 )
     *             ),
     *             @OA\Property(
     *                 property="meta",
     *                 type="object",
     *                 @OA\Property(property="current_page", type="integer"),
     *                 @OA\Property(property="last_page", type="integer"),
     *                 @OA\Property(property="per_page", type="integer"),
     *                 @OA\Property(property="total", type="integer")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error for invalid parameters.",
     *     )
     * )
     */
    public function index(Request $request)
    {
        $request->validate([
            'author_id' => 'nullable|exists:authors,id',
            'per_page' => 'nullable|integer|max:100',
        ]);

        $query = Post::with(['author', 'comments.author']);

        if ($request->filled('author_id')) {
            $query->where('author_id', $request->author_id);
        }

        if ($request->filled('title')) {
            $query->where('title', 'like', '%' . $request->title . '%');
        }

        $perPage = $request->get('per_page', 15);
        $posts = $query->paginate($perPage);

        $data = collect($posts->items())->map(function ($post) {
            return [
                'title' => $post->title,
                'author' => $post->author ? $post->author->name : null,  
                'comments' => $post->comments->map(function ($comment) {
                    return [
                        'name' => $comment->author ? $comment->author->name : null,  
                        'text' => $comment->text,
                    ];
                }),
            ];
        });

        return response()->json([
            'data' => $data,
            'meta' => [
                'current_page' => $posts->currentPage(),
                'last_page' => $posts->lastPage(),
                'per_page' => $posts->perPage(),
                'total' => $posts->total(),
            ],
        ]);
    }
}