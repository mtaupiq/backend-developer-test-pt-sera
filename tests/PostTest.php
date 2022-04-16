<?php

use Laravel\Lumen\Testing\WithoutMiddleware;

class PostTest extends TestCase
{
    use WithoutMiddleware;

    /**
     * /api/posts/mongodb [GET]
     */
    public function testShouldReturnAllPosts()
    {
        $this->get('/api/posts/mongodb', []);
        $this->seeStatusCode(200);
        $this->seeJsonStructure([
            'data' => ['*' => 
                [
                    '_id',
                    'title',
                    'author',
                    'body',
                    'updated_at',
                    'created_at'
                ]
            ]
        ]);
    }

    /**
     * /api/posts/firebase [GET]
     */
    public function testShouldReturnAllPostsFromFirebase()
    {
        $this->get('/api/posts/firebase', []);
        $this->seeStatusCode(200);
        $this->seeJsonStructure([
            'data' => ['*' => 
                [
                    'title',
                    'author',
                    'body'
                ]
            ]
        ]);
    }

}
