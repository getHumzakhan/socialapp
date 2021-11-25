<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use \App\Notifications\SignupNotification as Notification;

use MongoDB;

class Signin
{
    public function handle(Request $user_credentials, Closure $next)
    {
        $valid_user = $this->verify($user_credentials);

        if ($valid_user) {
            if ($valid_user['isVerified']) {

                $request = $user_credentials->merge($valid_user);
                return $next($request);
            } else {
                Notification::verify_account($valid_user);
                return response()->json(["Message" => "Please Verify Your Account First Via Link Sent To Your Email"], 400);
            }
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
            ["email" => $email, "password" => $password],
            ["projection" => ["_id" => 1, "name" => 1, "email" => 1, "password" => 1, "isVerified" => 1, "verificationToken" => 1]],
        );

        if (isset($document)) {
            return iterator_to_array($document);
        } else {
            return false;
        }
    }
}
