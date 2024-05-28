<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class UserPostFollowSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        // Crear usuarios
        $users = [];
        for ($i = 1; $i <= 10; $i++) {
            $users[] = [
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'password' => Hash::make('password'), // Usando una contraseña genérica
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        DB::table('users')->insert($users);

        // Obtener los IDs de los usuarios recién creados
        $userIds = DB::table('users')->pluck('id')->toArray();

        // Crear publicaciones para cada usuario
        $posts = [];
        foreach ($userIds as $userId) {
            for ($j = 1; $j <= 5; $j++) {
                $posts[] = [
                    'user_id' => $userId,
                    'title' => $faker->sentence,
                    'description' => $faker->paragraph,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }
        DB::table('posts')->insert($posts);

        // Obtener los IDs de los posts recién creados
        $postIds = DB::table('posts')->pluck('id')->toArray();

        // Crear imágenes para cada post
        $images = [];
        foreach ($postIds as $postId) {
            for ($k = 1; $k <= rand(1, 3); $k++) { // Cada post tiene de 1 a 3 imágenes
                $images[] = [
                    'post_id' => $postId,
                    'url' => $faker->imageUrl(), // URL de imagen generada por Faker
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }
        DB::table('images')->insert($images);

        // Crear follows entre los usuarios
        $follows = [];
        foreach ($userIds as $followerId) {
            // Cada usuario sigue a entre 2 y 5 otros usuarios
            $followingIds = $faker->randomElements($userIds, rand(2, 5));
            foreach ($followingIds as $followedId) {
                // Evitar que un usuario se siga a sí mismo
                if ($followerId !== $followedId) {
                    $follows[] = [
                        'user_id' => $followerId,
                        'followed_user_id' => $followedId,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }
        }
        DB::table('follows')->insert($follows);
    }
}
