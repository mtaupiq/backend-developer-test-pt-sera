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

    public function register(Request $request)
    { 
        $this->validatePayload($request);

        $url = 'api/register';
        $response = $this->response($request, $url);
        
        return response()->json($response, 200);
    }

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
