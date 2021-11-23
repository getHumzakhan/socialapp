<?php

namespace App\Http\Controllers;

use \App\Http\Requests\SignupRequest;
use \App\Notifications\SignupNotification as Notification;
use \App\Services\Response\Api;
use MongoDB;

class User extends Controller
{

    public function signup(SignupRequest $request_data)
    {
        //Register User
        $collection = (new MongoDB\Client)->socialapp->users;
        $document = $request_data->all();
        $insertOneResult = $collection->insertOne($document);

        //send email for account verification
        $notification = $request_data->all();
        Notification::verify_account($notification);

        //generate response with message and status code
        return Api::response(["message" => "Signup Successful! Account Verification Link has been sent to your email"], 200);
    }

    public function verify_signup_token($token)
    {
        $collection = (new MongoDB\Client)->socialapp->users;
        $document = $collection->findOne(["verificationToken" => $token]);

        if (isset($document['verificationToken'])) {

            $this->set_verified_flag($document['_id']);
            return Api::response(["Message" => "Account Verfied"], 200);
        } else {
            return Api::response(["Message" => "Invalid Token"], 422);
        }
    }

    //set isVerified flag to true in DB upon account confirmation
    public function set_verified_flag($user_id)
    {
        $collection = (new MongoDB\Client)->socialapp->users;
        $collection->updateOne(['_id' => $user_id], ['$set' => ['isVerified' => true]]);
    }
}
