<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use \App\Http\Requests\SignupRequest;

use MongoDB;

class Signin
{
    public function handle(Request $user_credentials, Closure $next)
    {
        $valid_user_document = $this->verify($user_credentials);

        if ($valid_user_document) {

            $request = $user_credentials->merge(["user_data" => $valid_user_document]);
            return $next($request);
        } else {
            return response()->json(["Message" => "Invalid Credentials"], 400);
        }
    }

    //returns matched user's document if provided credentials are valid, else false
    public function verify($user_credentials)
    {
        $email = $user_credentials->input('email');
        $password = $user_credentials->input('password');

        $collection = (new MongoDB\Client)->socialapp->users;
        $document = $collection->findOne(
            ["email" => $email],
            ["projection" => ["_id" => 1, "email" => 1, "password" => 1, "isVerified" => 1,]],
        );

        if (isset($document)) {
            if ($email === $document['email'] and $password === $document['password']) {
                return $document;
            }
        } else {
            return false;
        }
    }
}
