<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Http\Requests\CreatePostRequest;
use \App\Services\Response\API;
use MongoDB\Client as MongoDB;

class Post extends Controller
{
    protected $db;

    public function __construct()
    {
        $database = new MongoDB();
        $this->db = $database->socialapp;
    }

    // params: merged array having user id as _id and request data
    // uploades file and stores its url in db
    public function create(CreatePostRequest $request_data)
    {
        $user_id = strval($request_data['_id']);   //user id who is creating post
        $text = $request_data['text'];
        $file = $request_data->file('attachment');

        $destinationPath = 'uploads/' . strval($request_data['_id']);
        $file->move($destinationPath, $file->getClientOriginalName());
        $url = $destinationPath . "/" . $file->getClientOriginalName();

        $post = array("user_id" => $user_id, "text" => $text, "attachment" => base64_encode($url), "created_at" => date("d-m-y h-i-sa"));
        $this->db->posts->insertOne($post);
        return API::response(["Message" => "Post Successfully Created"], 200);
    }
}
