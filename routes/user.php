<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User;

Route::post('/signup', [User::class, 'signup']);
Route::get('/verifyAccount/{token}', [User::class, 'verify_signup_token']);

Route::post('/signin', [User::class, 'signin'])->middleware('signin');
