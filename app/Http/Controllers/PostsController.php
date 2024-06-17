<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use Illuminate\Support\Facades\Storage;
use App\Models\Image as ImageModel;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;

class PostsController extends Controller
{
    public function index()
    {
        $posts = Post::latest()->get();
        return PostResource::collection($posts);
    }

    private function createImageFromBase64($base64)
    {
        $imageData = base64_decode($base64);
        if ($imageData === false) {
            throw new \Exception('Base64 decoding failed.');
        }
        $f = finfo_open();
        $mimeType = finfo_buffer($f, $imageData, FILEINFO_MIME_TYPE);

        switch ($mimeType) {
            case 'image/jpeg':
            case 'image/png':
            case 'image/gif':
            case 'image/svg':
            case 'image/webp':
            case 'image/tiff':
            case 'image/heic':
            case 'image/heif':
            case 'image/avif':
            case 'image/jpg':
                return imagecreatefromstring($imageData);
            default:
                throw new \Exception('Unsupported image type: ' . $mimeType);
        }
    }

    public function store(Request $request)
    {
        $user = Auth::guard('sanctum')->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401)
                ->header('Access-Control-Allow-Origin', 'https://www.cargram.asalamero.dawmor.cloud')
                ->header('Access-Control-Allow-Credentials', 'true');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'images' => 'required|array',
            'images.*' => 'required|string',
            'description' => 'nullable|string|max:1000',
            'tag_ppl' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'aliasLocation' => 'nullable|string'
        ]);

        $post = new Post();
        $post->title = $request->title;
        $post->description = $request->description;
        $post->tag_ppl = $request->tag_ppl;
        $post->location = $request->location;
        $post->latitude = $request->latitude;
        $post->longitude = $request->longitude;
        $post->user_id = $user->id;
        $post->aliasLocation = $request->aliasLocation;
        $post->save();

        foreach ($request->images as $imageData) {
            try {
                if (strpos($imageData, ',') !== false) {
                    $imageData = substr($imageData, strpos($imageData, ',') + 1);
                }

                $img = $this->createImageFromBase64($imageData);

                if (!$img) {
                    throw new \Exception("Error al crear la imagen desde la cadena base64.");
                }

                $imageName = 'post_' . time() . '_' . uniqid() . '.webp';
                $imagePath = storage_path('app/public/' . $imageName);

                if (!imagewebp($img, $imagePath)) {
                    throw new \Exception("Error al guardar la imagen en formato WebP.");
                }

                imagedestroy($img);

                $imageUrl = Storage::url($imageName);

                $imageModel = new ImageModel();
                $imageModel->url = $imageUrl;
                $imageModel->post_id = $post->id;
                $imageModel->save();
            } catch (\Exception $e) {
                return response()->json(['message' => 'Error al procesar la imagen', 'error' => $e->getMessage()], 422)
                    ->header('Access-Control-Allow-Origin', 'https://www.cargram.asalamero.dawmor.cloud')
                    ->header('Access-Control-Allow-Credentials', 'true');
            }
        }

        return response()->json(['message' => 'Publicación creada exitosamente', 'post' => $post], 201)
            ->header('Access-Control-Allow-Origin', 'https://www.cargram.asalamero.dawmor.cloud')
            ->header('Access-Control-Allow-Credentials', 'true');
    }


    public function show(Post $post)
    {
        return new PostResource($post);
    }

    public function update(Request $request, Post $post)
    {
        // Validar los datos
        $request->validate([
            'title' => 'required',
            'content' => 'required',
            // Agrega aquí reglas de validación adicionales según tus necesidades
        ]);

        // Actualizar la publicación
        $post->title = $request->title;
        $post->content = $request->content;
        $post->save();

        return new PostResource($post);
    }

    public function destroy(Post $post)
    {
        $post->delete();
        return response()->json(null, 204);
    }
    public function getUserPosts(Request $request)
    {
        $user = $request->user(); // Suponiendo que usas autenticación y el usuario está autenticado
        $posts = Post::where('user_id', $user->id)->get();

        return response()->json($posts);
    }
    public function getPostsMap()
    {
        $posts = Post::whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->select('id', 'title', 'latitude', 'longitude')
            ->get();
        return response()->json($posts);
    }

    public function getUniquePost($id)
    {
        try {
            // Obtener el post por ID, incluyendo el usuario, likes, comentarios y las imágenes
            $post = Post::with(['user', 'likes', 'comments.user', 'images'])->findOrFail($id);

            // Mapear las imágenes para devolver la URL completa
            $post->images = $post->images->map(function ($image) {
                return [
                    'id' => $image->id,
                    'post_id' => $image->post_id,
                    'url' => url(Storage::url($image->url)),
                    'created_at' => $image->created_at,
                    'updated_at' => $image->updated_at,
                ];
            });

            return response()->json($post, 200);
        } catch (ModelNotFoundException $e) {
            // Si no se encuentra el post, devolver un error 404
            return response()->json(['message' => 'Post not found'], 404);
        } catch (\Exception $e) {
            // Manejar cualquier otro error
            return response()->json(['message' => 'An error occurred while fetching the post'], 500);
        }
    }

    public function getRandomPosts(Request $request)
    {
        $page = $request->input('page', 1);
        $perPage = 12;

        $posts = Post::with(['images', 'user', 'likes', 'comments.user'])
            ->inRandomOrder()
            ->paginate($perPage, ['*'], 'page', $page);

        return response()->json($posts, 200);
    }
};
