<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/signup', [SignupController::class, 'signup'])->middleware('signup');
