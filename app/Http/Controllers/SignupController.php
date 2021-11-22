<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SignupController extends Controller
{
    public function signup(Request $request_data)
    {
        echo $request_data->input('email');
    }
}
