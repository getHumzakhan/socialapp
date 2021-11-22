<?php

namespace App\Http\Controllers;

// use Illuminate\Http\Request;
use App\Http\Requests\SignupRequest;
use Symfony\Component\VarDumper\VarDumper;

class SignupController extends Controller
{
    public function signup(SignupRequest $request_data)
    {
        $validated_data = $request_data->validated();
        var_dump($validated_data);
    }
}
