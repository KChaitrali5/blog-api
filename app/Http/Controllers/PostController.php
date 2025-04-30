<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Post::with('user', 'comments.user')->paginate(5);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'body' => 'required',
        ]);
    
        $post = $request->user()->posts()->create($request->only('title', 'body'));
    
        return response()->json($post, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return $post->load('user', 'comments.user');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Find the post by ID
        $post = Post::find($id);

        // Check if the post exists
        if (!$post) {
            return response()->json(['message' => 'Post not found'], 404);
        }

        // Validate the input data (optional but recommended)
        $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
        ]);

        // Update the post with new data
        $post->update([
            'title' => $request->title,
            'body' => $request->body,
        ]);

        // Return the updated post
        return response()->json($post, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = Post::find($id); // Ensure the post is found by the id

        if (!$post) {
            return response()->json(['message' => 'Post not found'], 404);
        }

        $post->delete(); // Delete the post

        return response()->json(['message' => 'Post deleted successfully'], 200);
    }
}
