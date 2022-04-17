<?php

namespace App\Http\Controllers;

use App\User;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Laravel\Lumen\Routing\Controller as BaseController;

class AuthController extends BaseController
{
    /**
     * The request instance.
     *
     * @var Request
     */
    private $request;

    /**
     * Create a new controller instance.
     *
     * @param Request $request
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Create a new token.
     *
     * @param  User $user
     * @return string
     */
    protected function jwt(User $user)
    {
        $payload = [
            'iss' => "lumen-jwt",
            'sub' => $user->id,
            'iat' => time(),
            'exp' => time() + 60 * 60 * 2 
        ];

        return JWT::encode($payload, env('JWT_SECRET'), 'HS256');
    }

    /**
     * @OA\Post(
     *     path="/api/login",
     *     summary="Authenticate a user",
     *     description="Authenticate a user and return the token if the provided credentials are correct.",
     *     operationId="authenticate",
     *     tags={"2. Authenticate"},
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
    public function authenticate(User $user)
    {
        $this->validate($this->request, [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $this->request->input('email'))->first();
        
        if (!$user) {
            return response()->json([
                'error' => 'Email or password is wrong.'
            ], 422);
        }

        if (Hash::check($this->request->input('password'), $user->password)) {
            $token = $this->jwt($user);
            $user->update([
                'token' => $token,
            ]);
            return response()->json([
                'token' => $token
            ], 200);
        }

        return response()->json([
            'error' => 'Email or password is wrong.'
        ], 422);
    }

    /**
     * @OA\Post(
     *     path="/api/register",
     *     summary="Register a user",
     *     operationId="register",
     *     tags={"2. Authenticate"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="User object",
     *         @OA\JsonContent(ref="#/components/schemas/RegisterRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="response",
     *         @OA\JsonContent(ref="#/components/schemas/RegisterResponse"),
     *     )
     * )
     * @param  User $user
     * @return mixed
     * @throws ValidationException
     */
    public function register(Request $request)
    {
        $this->validate($request, [
            'name' => 'string',
            'email' => 'required|email|unique:user',
            'password' => 'required'
        ]);

        $user = User::where('email', $this->request->input('email'))->first();

        if ($user) {
            return response()->json([
                'error' => 'Email already exist.'
            ], 422);
        }

        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => password_hash($request->input('password'), PASSWORD_BCRYPT),
        ]);

        $response = [
            'status' => 'success',
            'message' => 'user created',
        ];

        return response()->json($response, 200);
    }

    /**
     * @OA\Post(
     *     path="/api/logout",
     *     summary="logout a user",
     *     operationId="logout",
     *     tags={"2. Authenticate"},
     *     @OA\Response(
     *         response=200,
     *         description="response",
     *         @OA\JsonContent(ref="#/components/schemas/RegisterResponse"),
     *     )
     * )
     * @param  User $user
     * @return mixed
     * @throws ValidationException
     */
    public function logout(Request $request)
    {
        $authUser = $request->auth;

        $user = User::where('_id',$authUser->id)->first();

        $user->update([
            'token' => null,
        ]);

        $response = [
            'status' => 'success',
            'message' => 'user logout.',
        ];

        return response()->json($response, 200);
    }

}
