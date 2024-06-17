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
| Aquí puedes registrar las rutas de la API para tu aplicación. Estas
| rutas son cargadas por el RouteServiceProvider dentro de un grupo que
| se asigna al grupo de middleware "api". ¡Disfruta construyendo tu API!
|
*/

Route::get('/test-session', function () {
    return session()->all();
});

// Ruta para obtener la cookie CSRF
Route::middleware('web')->get('/sanctum/csrf-cookie', function (Request $request) {
    return response()->json(['message' => 'CSRF cookie set']);
});

// Rutas públicas para autenticación y registro
Route::post('/login', [AuthController::class, 'login']);       // Inicio de sesión
Route::post('/register', [AuthController::class, 'register']); // Registro de nuevo usuario
Route::post('/newpost', [PostsController::class, 'store']); // Crear una nueva publicación

// Rutas protegidas por el middleware 'auth:sanctum'
Route::middleware('auth:sanctum')->group(function () {

    // Rutas para gestión de publicaciones

    // Rutas para autenticación
    Route::post('/logout', [AuthController::class, 'logout']);  // Cerrar sesión

    // Rutas para seguimiento de usuarios
    Route::get('/following', [UserController::class, 'following']); // Obtener lista de seguidos
    Route::post('/follow', [UserController::class, 'follow']);      // Seguir a un usuario
    Route::post('/unfollow', [UserController::class, 'unFollow']);  // Dejar de seguir a un usuario
    Route::post('/check-following', [UserController::class, 'checkFollowing']); // Verificar si se sigue a un usuario específico
    Route::get('/followed-users', [UserController::class, 'getMutualFollowers']); // Obtener usuarios seguidos mutuamente
    Route::get('/followers', [UserController::class, 'getFollowers']);

    // Rutas para el feed de usuario
    Route::get('/feed', [UserController::class, 'feed']); // Obtener publicaciones del feed
    Route::get('/feed/mapa', [PostsController::class, 'getPostsMap']); // Obtener personas aleatorias
    Route::get('/random-posts', [PostsController::class, 'getRandomPosts']);

    // Rutas para gestión de usuarios
    Route::get('/users', [UserController::class, 'index']);           // Listar todos los usuarios
    Route::get('/user', [UserController::class, 'getUser']);          // Obtener datos del usuario autenticado
    Route::get('/ajustes', [UserController::class, 'ajustes']);     // Obtener configuraciones del usuario

    // Ajustes de usuario
    Route::get('/settings', [UserController::class, 'settings']);     // Obtener configuraciones del usuario
    Route::put('/settings', [UserController::class, 'updateSettings']);
    // Ruta para subir la foto de perfil
    Route::post('/upload-profile-photo', [UserController::class, 'uploadProfilePhoto']);
    Route::get('/users/search', [UserController::class, 'search']);   // Buscar usuarios

    // Rutas para publicaciones
    Route::post('/posts/{post}/like', [UserController::class, 'like']);        // Dar "me gusta" a una publicación
    Route::post('/posts/{post}/comment', [UserController::class, 'storeComment']); // Comentar en una publicación
    Route::get('/user/posts', [PostsController::class, 'getUserPosts']);
    Route::get('/posts/{id}', [PostsController::class, 'getUniquePost']);
    Route::put('/posts/{id}', [PostsController::class, 'update']);
    Route::delete('/posts/{id}', [PostsController::class, 'destroy']);

    // Rutas para gestión de imágenes de perfil
    Route::post('/user/profile-image', [UserController::class, 'updateProfileImage']); // Actualizar imagen de perfil
    Route::post('/profile-photo', [UserController::class, 'updateProfilePhoto']); // Actualizar foto de perfil

    // Rutas para gestión de mensajes
    Route::get('/conversations', [MessageController::class, 'getConversations']);
    Route::get('/messages', [MessageController::class, 'index']);  // Listar mensajes
    // Route::get('/messages/{chatId}', [MessageController::class, 'getMessages']);
    Route::get('/messages/{userId}', [MessageController::class, 'getMessages']);
    Route::post('/messages', [MessageController::class, 'store']); // Enviar un nuevo mensaje
    // Rutas varias
    Route::get('/random-personas', [UserController::class, 'getRandomPersonas']); // Obtener personas aleatorias
});
