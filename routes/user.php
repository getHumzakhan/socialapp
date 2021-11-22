<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SignupController;

Route::post('/signup', [SignupController::class, 'signup']);
