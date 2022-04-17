<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    /**
     * @OA\Info(
     *   title="Backend Developer Test",
     *   version="1.0",
     *   description="API Documentation for backend developer test provided by PT. SERA (poin 9)",
     *   @OA\Contact(
     *     email="mtaupiq@gmail.com",
     *     name="Muhammad Taupiq"
     *   )
     * )
     */
    
    /**
     * @OA\Components(
     *     @OA\Schema(
     *         schema="Post",
     *         @OA\Xml(name="Post"),
     *                 @OA\Property(
     *                     property="_id",
     *                     type="string",
     *                     description="ID",
     *                     example="625a8dad57361f68e1010936"
     *                 ),
     *                 @OA\Property(
     *                     property="title",
     *                     type="string",
     *                     description="Title",
     *                     example="Create REST API With Laravel Lumen"
     *                 ),
     *                 @OA\Property(
     *                     property="author",
     *                     type="string",
     *                     description="Author Name",
     *                     example="Muhammad Taupiq"
     *                 ),
     *                 @OA\Property(
     *                     property="body",
     *                     type="string",
     *                     description="Post Body",
     *                     example="This is body post of Create REST API With Laravel Lumen"
     *                 ),
     *                 @OA\Property(
     *                     property="updated_at",
     *                     type="string",
     *                     description="Updated At",
     *                     example="2022-04-16T13:57:44.977000Z"
     *                 ),
     *                 @OA\Property(
     *                     property="created_at",
     *                     type="string",
     *                     description="Created At",
     *                     example="2022-04-16T13:57:44.977000Z"
     *                 )
     *     ),
     *     @OA\Schema(
     *         schema="PostRequest",
     *         type="object",
     *         title="PostRequest",
     *         required={"title", "body"},
     *         properties={
     *             @OA\Property(property="title", type="string"),
     *             @OA\Property(property="body", type="string")
     *         }
     *     ),
     *     @OA\Schema(
     *         schema="PostResponse",
     *         type="object",
     *         title="PostResponse",
     *         properties={
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
     *                 example="posts"
     *             ),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 description="Response Data",
     *                 ref="#/components/schemas/Post"
     *             )
     *         }
     *     ),
     *     @OA\Schema(
     *         schema="PostResponseDelete",
     *         type="object",
     *         title="PostResponseDelete",
     *         properties={
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
     *                 example="post deleted"
     *             )
     *         }
     *     ),
     *     @OA\Schema(
     *         schema="AuthRequest",
     *         type="object",
     *         title="AuthRequest",
     *         required={"email", "password"},
     *         properties={
     *             @OA\Property(property="email", type="string"),
     *             @OA\Property(property="password", type="string")
     *         }
     *     ),
     *     @OA\Schema(
     *         schema="AuthResponse",
     *         type="object",
     *         title="AuthResponse",
     *         properties={
     *             @OA\Property(
     *                 property="token",
     *                 type="string",
     *                 description="Token"
     *             )
     *         }
     *     ),
     *     @OA\Schema(
     *         schema="RegisterRequest",
     *         type="object",
     *         title="RegisterRequest",
     *         required={"email", "password"},
     *         properties={
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="email", type="string"),
     *             @OA\Property(property="password", type="string")
     *         }
     *     ),
     *     @OA\Schema(
     *         schema="RegisterResponse",
     *         type="object",
     *         title="RegisterResponse",
     *         properties={
     *             @OA\Property(
     *                 property="status",
     *                 type="string",
     *                 description="Response Status",
     *                 example="success"
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 description="Response message",
     *                 example="user created"
     *             )
     *         }
     *     )
     * )
     */
}
