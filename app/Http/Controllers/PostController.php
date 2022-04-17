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

    /**
     * @OA\Get(
     *     path="/api/posts/mongodb",
     *     summary="List blog post",
     *     operationId="index",
     *     tags={"1. MongoDB"},
     *     @OA\Parameter(
     *         name="token",
     *         in="query",
     *         description="Token required",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Returns all posts from mongodb",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="status",
     *                 type="string",
     *                 description="Response Status",
     *                 example="success"
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 description="Response Message",
     *                 example="posts list"
     *             ),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 description="Response Data",
     *                 @OA\Items(ref="#/components/schemas/Post")
     *             )
     *         )
     *     )
     * )
     */
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

    /**
     * @OA\Post(
     *     path="/api/posts/mongodb/create",
     *     summary="New blog post",
     *     operationId="create",
     *     tags={"1. MongoDB"},
     *     @OA\Parameter(
     *         name="token",
     *         in="query",
     *         description="Token required",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Post object",
     *         @OA\JsonContent(ref="#/components/schemas/PostRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="A post",
     *         @OA\JsonContent(ref="#/components/schemas/PostResponse"),
     *     )
     * )
     * @param Request $request
     * @return array
     */
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
            'data' => $post
        ];

        return response()->json($response, 200);
    }

    /**
     * @OA\Get(
     *     path="/api/posts/mongodb/{id}",
     *     summary="Get post by id",
     *     operationId="show",
     *     tags={"1. MongoDB"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Post id",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="token",
     *         in="query",
     *         description="Token required",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="A post",
     *         @OA\JsonContent(ref="#/components/schemas/PostResponse"),
     *     )
     * )
     * @param $id
     * @return array
     */
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

    /**
     * @OA\Put(
     *     path="/api/posts/mongodb/update/{id}",
     *     summary="Update blog post",
     *     operationId="update",
     *     tags={"1. MongoDB"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Post id",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="token",
     *         in="query",
     *         description="Token required",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Post object",
     *         @OA\JsonContent(ref="#/components/schemas/PostRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="A post",
     *         @OA\JsonContent(ref="#/components/schemas/PostResponse"),
     *     )
     * )
     * @param Request $request
     * @param $id
     * @return array
     */
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

    /**
     * @OA\Delete(
     *     path="/api/posts/mongodb/{id}",
     *     summary="Delete blog post",
     *     operationId="delete",
     *     tags={"1. MongoDB"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Post id",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="token",
     *         in="query",
     *         description="Token required",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="A post",
     *         @OA\JsonContent(ref="#/components/schemas/PostResponseDelete"),
     *     )
     * )
     * @param $id
     * @return array
     */
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
