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
