<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Http\Requests\CreatePostRequest;
use \App\Http\Requests\DeletePostRequest;
use \App\Services\Response\API;
use Illuminate\Support\Facades\File;
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

    public function delete(DeletePostRequest $request_data)
    {
        $post_id = $request_data['post_id'];   //post id that is to be deleted
        $post_id = new \MongoDB\BSON\ObjectId($post_id);
        $authentic_user_id = strval($request_data['_id']);       //user who wants to delete post

        $post = $this->db->posts->findOne(["_id" => $post_id]);

        if (isset($post)) {
            if ($post->user_id === $authentic_user_id) {

                $this->db->posts->deleteOne(['_id' => $post_id]);
                $file_path = public_path() . '/' . base64_decode($post->attachment);
                FILE::delete($file_path);

                return API::response(["Message" => "Post Deleted"], 200);
            } else {
                return API::response(["Message" => "Unauthorized Request"], 401);
            }
        } else {
            return API::response(["Message" => "Post Not Found"], 404);
        }
    }
}
