<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Like;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Image;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return response()->json([
            'success' => true,
            'users' => $users,
        ], 200);
    }

    /**
     * Get the authenticated user's information.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUser(Request $request)
    {
        // Obtener el usuario autenticado
        $user = Auth::guard('sanctum')->user();

        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        // Retornar la información del usuario
        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
        ]);
    }

    public function ajustes()
    {
        $user = Auth::guard('sanctum')->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        // Cargamos la cuenta de publicaciones y los usuarios que sigue con la cuenta de sus seguidores
        $user->loadCount('posts');
        $user->load(['following' => function ($query) {
            $query->withCount('followers')->select('user_id', 'name', 'email', 'profile_photo');
        }]);
        $prof = url($user->profile_photo);

        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'profile_photo' => $prof,
            'posts_count' => $user->posts_count,
            'following' => $user->following->map(function ($followedUser) {
                $profilePhotoUrl = null;
                if ($followedUser->profile_photo) {
                    $profilePhotoUrl = url(url($followedUser->profile_photo));
                }
                return [
                    'id' => $followedUser->id,
                    'name' => $followedUser->name,
                    'email' => $followedUser->email,
                    'profile_photo' => $profilePhotoUrl,
                    'followers_count' => $followedUser->followers_count,
                ];
            })
        ]);
    }



    public function settings()
    {
        // Autenticar al usuario con el guard 'sanctum'
        $user = Auth::guard('sanctum')->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        // Obtener las publicaciones del usuario autenticado
        $posts = \App\Models\Post::where('user_id', $user->id)
            ->with(['images', 'likes', 'comments.user']) // Incluir relaciones de likes, comments con el usuario
            ->orderBy('posts.created_at', 'desc')
            ->select('posts.id', 'posts.title', 'posts.description', 'posts.user_id', 'posts.created_at')
            ->get();

        // Mapear las publicaciones a la respuesta deseada
        $postsResponse = $posts->map(function ($post) {
            return [
                'id' => $post->id,
                'title' => $post->title,
                'content' => $post->description,
                'images' => $post->images->map(function ($image) {
                    // Generar la URL completa de la imagen
                    $url = Storage::url($image->url);
                    return url('/') . '/storage/' . basename($url);
                }),
                'created_at' => $post->created_at->toDateTimeString(),
                'likes' => $post->likes->map(function ($like) {
                    return [
                        'id' => $like->id,
                        'user_id' => $like->user_id,
                        'created_at' => $like->created_at->toDateTimeString(),
                        'updated_at' => $like->updated_at->toDateTimeString(),
                    ];
                }),
                'comments' => $post->comments->map(function ($comment) {
                    return [
                        'id' => $comment->id,
                        'user' => [
                            'id' => $comment->user->id,
                            'name' => $comment->user->name,
                        ],
                        'text' => $comment->text,
                        'created_at' => $comment->created_at->toDateTimeString(),
                    ];
                }),
            ];
        });

        // Cargar la cuenta de seguidores y seguidos del usuario
        $user->loadCount('followers', 'following');

        // Cargar los datos de los usuarios que sigue
        $user->load(['following' => function ($query) {
            $query->withCount('followers')->select('users.id', 'users.name', 'users.email', 'users.profile_photo');
        }]);

        // Generar URL de la foto de perfil del usuario
        $prof = $user->profile_photo ? url($user->profile_photo) : null;

        // Retornar la respuesta en formato JSON
        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'profile_photo' => $prof,
            'posts' => $postsResponse,
            'followers_count' => $user->followers_count,
            'following_count' => $user->following_count,
            'following' => $user->following->map(function ($followedUser) {
                $profilePhotoUrl = $followedUser->profile_photo ? url($followedUser->profile_photo) : null;
                return [
                    'id' => $followedUser->id,
                    'name' => $followedUser->name,
                    'email' => $followedUser->email,
                    'profile_photo' => $profilePhotoUrl,
                    'followers_count' => $followedUser->followers_count,
                ];
            })
        ]);
    }




    public function updateSettings(Request $request)
    {
        $user = Auth::guard('sanctum')->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $user->update($request->only('name', 'email', 'profile_image'));

        return response()->json(['message' => 'Settings updated successfully']);
    }

    public function uploadProfilePhoto(Request $request)
    {
        $user = Auth::guard('sanctum')->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        if ($request->hasFile('profile_photo')) {
            $path = $request->file('profile_photo')->store('profile_photos', 'public');
            $user->profile_image = $path;
            $user->save();

            return response()->json(['profile_photo' => url(Storage::url($path))]);
        }

        return response()->json(['message' => 'No file uploaded'], 400);
    }



    public function following()
    {
        $user = Auth::user();
        $following = $user->following;
        return response()->json([
            'success' => true,
            'following' => $following,
        ], 200);
    }

    // Seguir a un usuario
    public function follow(Request $request)
    {
        $user = Auth::guard('sanctum')->user();
        $userToFollow = User::find($request->user_id);

        if ($userToFollow && !$user->following()->find($userToFollow->id)) {
            $user->following()->attach($userToFollow->id);
            return response()->json([
                'success' => true,
                'message' => 'User followed successfully',
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'Unable to follow user',
        ], 400);
    }

    public function unFollow(Request $request)
    {
        $user = Auth::guard('sanctum')->user();
        $userToUnfollow = User::find($request->user_id);

        if (!$userToUnfollow) {
            return response()->json([
                'success' => false,
                'message' => 'User not found',
            ], 404);
        }

        if ($user->following()->find($userToUnfollow->id)) {
            // El usuario está siguiendo, así que dejamos de seguir
            $user->following()->detach($userToUnfollow->id);
            return response()->json([
                'success' => true,
                'message' => 'User unfollowed successfully',
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'You are not following this user',
        ], 400);
    }



    public function feed()
    {
        // Autenticar al usuario con el guard 'sanctum'
        $user = Auth::guard('sanctum')->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        // Obtener los IDs de los usuarios que el usuario autenticado sigue
        $followingIds = \App\Models\User::join('follows', 'users.id', '=', 'follows.followed_user_id')
            ->where('follows.user_id', $user->id)
            ->select('users.id')
            ->get()
            ->pluck('id')
            ->toArray();

        // Verificar si el usuario no sigue a nadie
        if (empty($followingIds)) {
            return response()->json(['message' => 'No sigues a nadie. Empieza a seguir a otros usuarios para ver sus publicaciones.'], 200);
        }

        // Incluir el ID del usuario autenticado en la lista
        $followingIds[] = $user->id;

        // Obtener las publicaciones de los usuarios seguidos y del propio usuario autenticado
        $posts = \App\Models\Post::whereIn('user_id', $followingIds)
            ->with(['images', 'likes', 'comments.user', 'user']) // Incluir relaciones de likes, comments con el usuario, y el autor del post
            ->orderBy('posts.created_at', 'desc')
            ->select('posts.id', 'posts.title', 'posts.description', 'posts.user_id', 'posts.created_at')
            ->get();

        // Mapear las publicaciones a la respuesta deseada
        $response = $posts->map(function ($post) {
            return [
                'id' => $post->id,
                'title' => $post->title,
                'content' => $post->description,
                'images' => $post->images->map(function ($image) {
                    // Generar la URL completa de la imagen
                    $url = Storage::url($image->url);
                    return url('/') . '/storage/' . basename($url);
                }),
                'user' => [
                    'id' => $post->user->id,
                    'name' => $post->user->name,
                ],
                'created_at' => $post->created_at->toDateTimeString(),
                'likes' => $post->likes->map(function ($like) {
                    return [
                        'id' => $like->id,
                        'user_id' => $like->user_id,
                        'created_at' => $like->created_at->toDateTimeString(),
                        'updated_at' => $like->updated_at->toDateTimeString(),
                    ];
                }),
                'comments' => $post->comments->map(function ($comment) {
                    return [
                        'id' => $comment->id,
                        'user' => [
                            'id' => $comment->user->id,
                            'name' => $comment->user->name,
                        ],
                        'text' => $comment->text,
                        'created_at' => $comment->created_at->toDateTimeString(),
                    ];
                }),
            ];
        });

        // Retornar la respuesta en formato JSON
        return response()->json(['posts' => $response], 200);
    }

    public function checkFollowing(Request $request)
    {
        $user = Auth::guard('sanctum')->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $request->validate([
            'user_id' => 'required|integer|exists:users,id'
        ]);

        $isFollowing = Follow::where('user_id', $user->id)
            ->where('followed_user_id', $request->user_id)
            ->exists();
        if (!$isFollowing) {
            return response()->json(['Not Following']);
        } else {
            return response()->json(['following' => $isFollowing], 200);
        }
    }

    public function like(Request $request, $postId)
    {
        $user = Auth::guard('sanctum')->user();

        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $post = Post::find($postId);

        if (!$post) {
            return response()->json(['message' => 'Post not found'], 404);
        }

        $like = $post->likes()->where('user_id', $user->id)->first();

        if ($like) {
            // Si ya hay un like, eliminarlo (unlike)
            $like->delete();
            return response()->json(['liked' => false]);
        } else {
            // Si no hay un like, agregarlo
            $post->likes()->create(['user_id' => $user->id]);
            return response()->json(['liked' => true]);
        }
    }

    public function storeComment(Request $request, Post $post)
    {
        $user = Auth::guard('sanctum')->user();

        $validatedData = $request->validate([
            'text' => 'required|string|max:255',
        ]);

        $comment = Comment::create([
            'post_id' => $post->id,
            'user_id' => $user->id,
            'text' => $validatedData['text'],
        ]);

        return response()->json($comment, 201);
    }


    public function search(Request $request)
    {
        $query = $request->input('query');

        // Obtener el usuario autenticado
        $authUser = Auth::guard('sanctum')->user();
        if (!$authUser) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        // Buscar usuarios por nombre de usuario y comprobar si están seguidos por el usuario autenticado
        $users = User::where('name', 'LIKE', "%{$query}%")
            ->leftJoin('follows as f', function ($join) use ($authUser) {
                $join->on('users.id', '=', 'f.followed_user_id')
                    ->where('f.user_id', '=', $authUser->id);
            })
            ->select('users.*', DB::raw('IF(f.user_id IS NULL, false, true) as is_following'))
            ->limit(5) // Limitar los resultados a 5
            ->get();

        // Generar la URL completa para la imagen de perfil
        $users = $users->map(function ($user) {
            $profilePhotoUrl = null;
            if ($user->profile_photo) {
                $profilePhotoUrl = url($user->profile_photo);
            }
            $user->profile_photo = $profilePhotoUrl;
            return $user;
        });

        return response()->json([
            'success' => true,
            'users' => $users,
        ], 200);
    }


    public function getRandomPersonas()
    {
        $personas = User::inRandomOrder()->take(10)->get(['id', 'name as nombre', 'email', 'is_following', 'profile_image as imagen']);
        return response()->json($personas);
    }

    public function getMutualFollowers(Request $request)
    {
        $user = $request->user();

        // Obtener los usuarios que el usuario sigue y que también lo siguen
        $mutualFollowers = $user->following()->whereHas('followers', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->get();

        return response()->json($mutualFollowers);
    }

    public function updateProfilePhoto(Request $request)
    {
        $request->validate([
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $user = Auth::user();

        if ($request->hasFile('profile_photo')) {
            // Borrar la foto anterior si existe
            if ($user->profile_photo) {
                Storage::delete($user->profile_photo);
            }

            // Guardar la nueva foto
            $path = $request->file('profile_photo')->store('public/profile_photos');
            $user->profile_photo = Storage::url($path);
        }

        $user->save();

        return response()->json(['message' => 'Profile updated successfully']);
    }
}
// {
//     "Version": "2012-10-17",
//     "Statement": [
//         {
//             "Effect": "Allow",
//             "Action": [
//                 "ec2-instance-connect:SendSSHPublicKey",
//                 "ec2:DescribeInstances"
//             ],
//             "Resource": "*",
//             "Condition": {
//                 "StringEquals": {
//                     "ec2:ResourceTag/Owner": "asalamero_aws"
//                 }
//             }
//         }
//     ]
// }