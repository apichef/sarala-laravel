<?php

use Illuminate\Support\Facades\Route;

Route::get('/users', 'UserController@index')
    ->name('users.index');

Route::get('/users/{user}', 'UserController@show')
    ->name('users.show');

Route::get('/users/{user}/posts', 'UserPostController@index')
    ->name('users.posts.index');

Route::get('/posts', 'PostController@index')
    ->name('posts.index');

Route::get('/posts/{post}', 'PostController@show')
    ->name('posts.show');

Route::get('/posts/{post}/comments', 'PostCommentController@index')
    ->name('posts.comments.index');

Route::get('/posts/{post}/tags', 'PostTagController@index')
    ->name('posts.tags.index');

Route::get('/tags', 'TagController@index')
    ->name('tags.index');

Route::get('/tags/{tag}/posts', 'TagPostController@index')
    ->name('tags.posts.index');

Route::get('/exception', function () {
    throw new \Sarala\Dummy\Exceptions\TestException();
})
    ->name('exception');
