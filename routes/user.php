<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use app\Http\Controllers\SignupController;

Route::post('/signup', [SignupController::class, 'signup'])->middleware('signup');
