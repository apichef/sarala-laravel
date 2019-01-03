<?php

use Illuminate\Support\Facades\Route;

Route::get('/posts/{post}', 'PostController@show')
    ->name('posts.show');
