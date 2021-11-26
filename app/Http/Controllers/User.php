<?php

namespace App\Http\Controllers;

use \Illuminate\Http\Request;
use \App\Http\Requests\SignupRequest;

use \App\Notifications\SignupNotification as Notification;
use \App\Services\Response\Api;
use App\Services\Auth\JwtAuth;
use MongoDB\Client as MongoDB;

class User extends Controller
{
    protected $db;

    public function __construct()
    {
        $database = new MongoDB();
        $this->db = $database->socialapp;
    }

    public function signup(SignupRequest $request_data)
    {
        $user = $request_data->all();
        //Register User
        $this->db->users->insertOne($user);

        //send email for account verification
        $notification = $request_data->all();
        Notification::verify_account($notification);

        //generate response with message and status code
        return Api::response(["message" => "Signup Successful! Account Verification Link has been sent to your email"], 200);
    }

    public function verify_signup_token($token)
    {
        $user = $this->db->users->findOne(["verificationToken" => $token]);

        if (isset($user['verificationToken'])) {

            $this->set_verified_flag($user['_id']);
            return Api::response(["Message" => "Account Verfied"], 200);
        } else {
            return Api::response(["Message" => "Invalid Token"], 422);
        }
    }

    //set isVerified flag to true in DB upon account confirmation
    public function set_verified_flag($user_id)
    {
        $this->db->users->updateOne(['_id' => $user_id], ['$set' => ['isVerified' => true]]);
    }

    //generates jwt for valid user and set it as cookie.
    public function signin(Request $request_data)
    {
        $id = $request_data['_id'];
        $name = $request_data['name'];
        $email = $request_data['email'];

        $jwt = JwtAuth::generate_jwt($id, $email);

        date_default_timezone_set("Asia/Karachi");

        setrawcookie("jwt", $jwt, time() + 10);

        return API::response(["Message" => "Welcome " . $name], 200);
    }
}
