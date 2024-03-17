<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\SaveController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//Users Api's-----------------------------------------------------------------------------------------------------
Route::apiResource('users',UserController::class);
Route::post('register',[UserController::class,'register']);
Route::post('login',[UserController::class,'login']);
Route::get('users/find/{email}', [UserController::class, 'searchByMail']);
Route::get('users/check/{email}/{code}', [UserController::class, 'checkCode']);
Route::post('users/reset/{email}', [UserController::class, 'resetPassword']);
Route::post('users/update/{user}', [UserController::class, 'updateUser']);


//Posts Api's---------------------------------------------------------------------------------------------------------
Route::apiResource('posts',PostController::class);
Route::put('/posts/{post}/{article_1}/{article_2}', [PostController::class, 'update']);
Route::put('/posts/{post}', [PostController::class, 'updateStatus']);
Route::get('/posts/user/{user_id}', [PostController::class, 'getByUserId']);
Route::get('/posts/category/{category_id}', [PostController::class, 'getByCategoryId']);
Route::get('/posts/status/{status}', [PostController::class,'getPostsByStatus']);

//Categories Api's-----------------------------------------------------------------------------------------------------
Route::apiResource('categories',CategoryController::class);

//Contacts Api's-------------------------------------------------------------------------------------------------------
Route::apiResource('contacts',ContactController::class);
Route::post('mail',[ContactController::class,'sendEmail']);

//Saves Api's----------------------------------------------------------------------------------------------------------
Route::apiResource('/saves',SaveController::class);
Route::get('/user-saves/{user_id}', [SaveController::class, 'getUserSaves']);

//Likes Api's----------------------------------------------------------------------------------------------------------
Route::apiResource('/likes',LikeController::class);
Route::get('/user-likes/{user_id}', [LikeController::class, 'getUserLikes']);
