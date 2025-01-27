<?php

namespace App\Http\Controllers;

use App\Models\Author;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    /**
     * Display a listing of authors.
     */
    public function index(Request $request)
    {
        // Pagination with optional per_page query parameter
        $authors = Author::paginate($request->get('per_page', 15)); 
        return response()->json([
            'data' => $authors->items(),
            'meta' => [
                'current_page' => $authors->currentPage(),
                'last_page' => $authors->lastPage(),
                'per_page' => $authors->perPage(),
                'total' => $authors->total(),
            ],
        ]);
    }

    /**
     * Store a newly created author in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:authors,name',
        ]);

        $author = Author::create($request->all());
        return response()->json($author, 201);
    }

    /**
     * Display the specified author.
     */
    public function show($id)
    {
        $author = Author::find($id);

        if (!$author) {
            return response()->json(['message' => 'Author not found'], 404);
        }

        return response()->json($author);
    }

    /**
     * Update the specified author in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:authors,name,' . $id, 
        ]);

        $author = Author::findOrFail($id);
        $author->update($request->all());

        return response()->json($author);
    }

    /**
     * Remove the specified author from storage.
     */
    public function destroy($id)
    {
        $author = Author::find($id);

        if (!$author) {
            return response()->json(['message' => 'Author not found'], 404);
        }

        $author->delete();
        return response()->json(null, 204);
    }
}
