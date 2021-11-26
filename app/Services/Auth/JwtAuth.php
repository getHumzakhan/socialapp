<?php

namespace App\Services\Auth;

use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;

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
}
