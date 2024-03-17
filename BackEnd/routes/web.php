<?php

use Illuminate\Support\Facades\File as FacadesFile;
use Illuminate\Support\Facades\Route;
use Spatie\Backtrace\File;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

//route to show post images
Route::get('/posts_images/{filename}', function ($filename) {
    $path = storage_path('../public/posts_images/' . $filename);
    if (!FacadesFile::exists($path)) {
        abort(404);
    }
    return response()->file($path);
});
