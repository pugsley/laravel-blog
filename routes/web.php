<?php

use App\Models\BlogPost;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('blog/api/posts', function() {
    return BlogPost::with('user')->get()->jsonSerialize();
});

Route::get('blog/{any?}', function() {
    return view('blog');
})->where('any', '.*');
