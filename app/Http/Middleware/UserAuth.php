<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\Auth\JwtAuth;
use App\Services\Response\Api;

class UserAuth
{
    public function handle(Request $request, Closure $next)
    {
        $jwt = $request->header('cookie');

        if (isset($jwt)) {

            $jwt = ltrim($jwt, "jwt=");
            $authorized_user = JwtAuth::verify($jwt);

            if ($authorized_user) {
                $request = $request->merge($authorized_user);
                return $next($request);
            } else {
                return API::response(["Message" => "Unauthorized Request"], 401);
            }
        } else {
            return Api::response(["Message" => "You Need To Sign In"], 401);
        }
    }
}
