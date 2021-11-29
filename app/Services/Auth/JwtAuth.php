<?php

namespace App\Services\Auth;

use \Firebase\JWT\JWT;
use MongoDB\Client as MongoDB;

class JwtAuth
{
    public static function generate_jwt($id, $email)
    {
        //define jwt payload
        $payload = array(
            "iss" => "localhost",
            "iat" => time(),
            "aud" => "merchant",
            "data" => array(
                "id" => strval($id),
                "email" => $email
            )
        );

        $jwt = JWT::encode($payload, config('jwt.secret_key'));
        return $jwt;

        // $decoded_jwt = JWT::decode($jwt, new Key($_ENV['JWT_SECRET_KEY'], 'HS256'));
    }

    //searches for provided jwt in DB and returns the respective user, else returns false
    public function verify_jwt($request)
    {
        $jwt = $request->header('Cookie');

        $db = (new MongoDB())->socialapp;
        $authorized_user = $db->users->findOne(['jwt' => $jwt], ['projection' => ['_id' => 1]]);

        if (isset($authorized_user)) {
            return iterator_to_array($authorized_user);
        } else {
            return false;
        }
    }
}
