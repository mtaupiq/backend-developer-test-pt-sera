<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;

class FirebaseController extends Controller
{
    protected $database;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->database = app('firebase.database');
    }

    /**
     * @OA\Get(
     *     path="/api/posts/firebase",
     *     summary="List blog post",
     *     operationId="index",
     *     tags={"3. Firebase"},
     *     @OA\Parameter(
     *         name="token",
     *         in="query",
     *         description="Token required",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Returns all posts from firebase",
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
        $reference = $this->database->getReference('posts');
        $value = $reference->getValue();

        $response = [
            'status' => 'success',
            'message' => 'posts list',
            'data' => $value,
        ];

        return response()->json($response, 200);
    }

    /**
     * @OA\Post(
     *     path="/api/posts/firebase/create",
     *     summary="New blog post",
     *     operationId="create",
     *     tags={"3. Firebase"},
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
        $uid = $request->auth->id;

        $postData = [
            'title' => $request->input('title'),
            'author' => $request->auth->name,
            'body' => $request->input('body'),
        ];

        $newPostKey = $this->database->getReference('posts')->push()->getKey();

        $updates = [
            'posts/'.$newPostKey => $postData,
            // 'user-posts/'.$uid.'/'.$newPostKey => $postData,
        ];

        try {
            $this->database->getReference()->update($updates);
        } catch (Exception $e) {
            return response()->json([
                'error' => $e
            ], 400);
        }

        $response = [
            'status' => 'success',
            'message' => 'post created',
        ];

        return response()->json($response, 200);
    }

    /**
     * @OA\Get(
     *     path="/api/posts/firebase/{id}",
     *     summary="Get post by id",
     *     operationId="show",
     *     tags={"3. Firebase"},
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
            $post = $this->database->getReference('posts')->orderByKey()->equalTo($id)->getValue();
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
     *     path="/api/posts/firebase/update/{id}",
     *     summary="Update blog post",
     *     operationId="update",
     *     tags={"3. Firebase"},
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
        $ref = 'posts/'.$id;

        $postData = [
            'title' => $request->input('title'),
            'author' => $request->auth->name,
            'body' => $request->input('body'),
        ];

        try {
            $this->database->getReference($ref)->set($postData);
            $post = $this->database->getReference($ref)->getValue();
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
     *     path="/api/posts/firebase/{id}",
     *     summary="Delete blog post",
     *     operationId="delete",
     *     tags={"3. Firebase"},
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
        $ref = 'posts/'.$id;

        try {
            $this->database->getReference($ref)->remove();
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
