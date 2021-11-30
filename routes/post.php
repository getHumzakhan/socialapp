<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Post;

Route::post('/create', [Post::class, 'create']);
Route::post('/delete', [Post::class, 'delete']);
