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
     * Authenticate a user and return the token if the provided credentials are correct.
     *
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
            ], 400);
        }

        if (Hash::check($this->request->input('password'), $user->password)) {
            return response()->json([
                'token' => $this->jwt($user)
            ], 200);
        }

        return response()->json([
            'error' => 'Email or password is wrong.'
        ], 400);
    }

    /**
     * Register a user 
     *
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
            ], 400);
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
}
