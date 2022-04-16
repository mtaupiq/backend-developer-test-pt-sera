<?php

namespace App\Http\Controllers;

use App\Post;
use Exception;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function index()
    {
        $posts = Post::all();

        $response = [
            'status' => 'success',
            'message' => 'posts list',
            'data' => $posts,
        ];

        return response()->json($response, 200);
    }

    public function create(Request $request)
    {
        $post = Post::create([
            'title' => $request->input('title'),
            'author' => $request->auth->name,
            'body' => $request->input('body'),
        ]);

        $response = [
            'status' => 'success',
            'message' => 'post created',
        ];

        return response()->json($response, 200);
    }

    public function show($id)
    {
        try {
            $post = Post::findOrFail($id);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Post not found.'
            ], 404);
        }

        $response = [
            'status' => 'success',
            'data' => $post,
        ];

        return response()->json($response, 200);
    }

    public function update(Request $request, $id)
    {
        try {
            $post = Post::findOrFail($id);
            $post->update($request->all());
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Post not found.'
            ], 404);
        }

        $response = [
            'status' => 'success',
            'message' => 'post updated',
            'data' => $post,
        ];
        return response()->json($response, 200);
    }

    public function delete($id)
    {
        try {
            $post = Post::findOrFail($id);
            $post->delete();
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Post not found.'
            ], 404);
        }

        $response = [
            'status' => 'success',
            'message' => 'post deleted',
        ];
        return response()->json($response, 200);
    }
}
