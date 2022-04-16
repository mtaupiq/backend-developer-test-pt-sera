<?php

use Laravel\Lumen\Testing\WithoutMiddleware;

class AuthTest extends TestCase
{
    use WithoutMiddleware;

    /**
     * /api/register [POST]
     */
    public function testShouldCreateUser()
    {
        $params = [
            "name"=>"Test User",
            "email"=>"test_user@gmail.com",
            "password"=>"123456"
        ];
        $this->post('/api/register', $params, []);
        $this->seeStatusCode(200);
        $this->seeJsonStructure(['status', 'message']);
    }

    /**
     * /api/login [POST]
     */
    public function testShouldReturnToken()
    {
        $params = [
            "email"=>"test_user@gmail.com",
            "password"=>"123456"
        ];
        $this->post('/api/login', $params, []);
        $this->seeStatusCode(200);
        $this->seeJsonStructure(['token']);
    }

}
