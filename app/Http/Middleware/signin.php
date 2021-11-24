<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use \App\Http\Requests\SignupRequest;
use MongoDB;

class signin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request_data, Closure $next)
    {
        if ($this->user_credentials_valid($request_data)) {
            return $next($request_data);
        } else {
            return response()->json(["Message" => "Invalid Credentials"], 400);
        }
    }

    //returns matched user's document if provided credentials are valid, else false
    public function user_credentials_valid(Request $request_data)
    {
        $email = $request_data->input('email');
        $password = $request_data->input('password');

        $collection = (new MongoDB\Client)->socialapp->users;
        $document = $collection->findOne(
            ["email" => $email],
            ["projection" => ["email" => 1, "password" => 1, "isVerified" => 1]],
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
