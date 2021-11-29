<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\Auth\JwtAuth;
use App\Services\Response\Api;

class CreatePost
{
    public function handle(Request $create_post, Closure $next)
    {
        $jwt = $create_post->header('cookie');

        if (isset($jwt)) {

            $jwt = ltrim($jwt, "jwt=");
            $authorized_user = JwtAuth::verify($jwt);

            if ($authorized_user) {
                $request = $create_post->merge($authorized_user);
                return $next($request);
            } else {
                return API::response(["Message" => "Unauthorized Request"], 401);
            }
        } else {
            return Api::response(["Message" => "You Need To Sign In"], 401);
        }
    }
}
