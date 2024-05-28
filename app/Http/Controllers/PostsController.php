<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use Illuminate\Support\Facades\Storage;
use App\Models\Image as ImageModel;
use Illuminate\Support\Facades\Auth;

class PostsController extends Controller
{
    public function index()
    {
        $posts = Post::latest()->get();
        return PostResource::collection($posts);
    }

    // Función para crear imágenes desde una cadena base64 con soporte para varios formatos
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
            case 'image/webp':
                return imagecreatefromstring($imageData);
            default:
                throw new \Exception('Unsupported image type: ' . $mimeType);
        }
    }

    public function store(Request $request)
    {
        $user = Auth::guard('sanctum')->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
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
                return response()->json(['message' => 'Error al procesar la imagen', 'error' => $e->getMessage()], 422);
            }
        }

        return response()->json(['message' => 'Publicación creada exitosamente', 'post' => $post], 201);
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
}
