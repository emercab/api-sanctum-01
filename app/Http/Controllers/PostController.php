<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class PostController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    return Post::all();
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request): JsonResponse
  {
    $data = $request->validate([
      'title' => 'required|max:255',
      'body' => 'required',
    ]);

    $post = $request->user()->posts()->create($data); // Create a post for the authenticated user

    return response()->json([
      'message' => 'Post created successfully!',
      'status' => 201,
      'post' => $post,
    ], 201);
  }

  /**
   * Display the specified resource.
   */
  public function show(Post $post): Post
  {
    return $post;
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, Post $post): JsonResponse
  {
    Gate::authorize('modify', $post);

    $data = $request->validate([
      'title' => 'required|max:255',
      'body' => 'required',
    ]);

    $post->update($data);

    return response()->json([
      'message' => 'Post updated successfully!',
      'status' => 200,
      'post' => $post,
    ], 200);
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(Post $post): JsonResponse
  {
    Gate::authorize('delete', $post);

    $post->delete();

    return response()->json([
      'message' => 'Post deleted successfully!',
      'status' => 200,
    ], 200);
  }
}
