<?php

use App\Models\BlogPost;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes();

Route::get('/', function () {
    return view('welcome');
});

// Redirect all requests to the Vue view/app
Route::get('blog/{any?}', function() {
    return view('blog');
})
    ->where('any', '.*')
    ->name('blog');

// In the interests of getting-stuff-done, I've added the api controller here
// Ideally it would live in the api routes file and then we'd use Laravel Passport to control access.
Route::resource('api/blog', 'Api\BlogController', ['except' => ['create', 'edit', 'show']]);

// TODO: Put this in it's own controller
Route::get('api/user', function() {
   $user = Auth::user();
   if ($user) {
       return $user;
   } else {
       return response()->json(null);
   }
});
