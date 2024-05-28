<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DatosController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostsController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MessageController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
//     return $request->user();
// });

// routes/api.php
// Route::post('/register', [AuthController::class, 'register']);
// Route::post('/login', [AuthController::class, 'login']);
// Route::post('/logout', [AuthController::class, 'logout']);

/**
 * Rutas de prueba 
 * Por eso estan comentadas (:)
 */
// Route::get('/datos', [DatosController::class, 'index']);
// Route::apiResource('users', AuthController::class);
// Route::get('/user', [AuthController::class, 'user']);


Route::middleware('web')->get('/sanctum/csrf-cookie', function (Request $request) {
    return response()->json(['message' => 'CSRF cookie set']);
});
Route::post('/login', [AuthController::class, 'login']);

/**
 * Rutas bajo el middleware de sanctum para que solo 
 * Se ejecuten las rutas con un token de autorizacion
 */
// routes/api.php

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/newpost', [PostsController::class, 'store']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/following', [UserController::class, 'following']);
    Route::post('/follow', [UserController::class, 'follow']);
    Route::post('/unfollow', [UserController::class, 'unFollow']);
    Route::get('/feed', [UserController::class, 'feed']);
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/user', [UserController::class, 'getUser']);
    Route::get('/settings', [UserController::class, 'settings']);
    Route::get('/users/search', [UserController::class, 'search']);
    Route::post('/posts/{post}/like', [UserController::class, 'like']);
    Route::post('/posts/{post}/comment', [UserController::class, 'storeComment']);
    Route::post('/check-following', [UserController::class, 'checkFollowing']);
    Route::post('/user/profile-image', [UserController::class, 'updateProfileImage']);
    Route::get('/random-personas', [UserController::class, 'getRandomPersonas']);
    Route::get('/messages', [MessageController::class, 'index']);
    Route::post('/messages', [MessageController::class, 'store']);
    Route::get('/followed-users', [UserController::class, 'getMutualFollowers']);
});

Route::post('/register', [AuthController::class, 'register']);


// Mirar seeders