<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;

class IntegrasiController extends Controller
{
    protected $client;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'https://reqres.in/',
            'timeout' => 5.0
        ]);
    }

    /**
     * @OA\Post(
     *     path="/reqres/register",
     *     summary="Register a user",
     *     description="6. Integrasi dengan reqres.in",
     *     operationId="register",
     *     tags={"6. reqres.in"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="User object",
     *         @OA\JsonContent(ref="#/components/schemas/AuthRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="token",
     *         @OA\JsonContent(ref="#/components/schemas/AuthResponse"),
     *     )
     * )
     * @param  User $user
     * @return mixed
     * @throws ValidationException
     */
    public function register(Request $request)
    { 
        $this->validatePayload($request);

        $url = 'api/register';
        $response = $this->response($request, $url);
        
        return response()->json($response, 200);
    }

    /**
     * @OA\Post(
     *     path="/reqres/login",
     *     summary="Authenticate a user",
     *     description="6. Integrasi dengan reqres.in",
     *     operationId="login",
     *     tags={"6. reqres.in"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="User object",
     *         @OA\JsonContent(ref="#/components/schemas/AuthRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="token",
     *         @OA\JsonContent(ref="#/components/schemas/AuthResponse"),
     *     )
     * )
     * @param  User $user
     * @return mixed
     * @throws ValidationException
     */
    public function login(Request $request)
    { 
        $this->validatePayload($request);

        $url = 'api/login';
        $response = $this->response($request, $url);

        return response()->json($response, 200);
    }

    private function response(Request $request, $url)
    {
        $response = $this->client->post($url, 
        [
            'json' => 
                [
                    'email' => $request->input('email'),
                    'password' => $request->input('password')
                ]
        ]);
        $body = $response->getBody();
        $data = json_decode($body, true);

        return $data;
    }

    private function validatePayload(Request $request)
    {
        return $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required'
        ]);
    }
}
