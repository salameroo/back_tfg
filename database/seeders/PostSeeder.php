<?php

use Illuminate\Support\Facades\Storage;

// Asegúrate de que estás en el contexto de tu aplicación Laravel
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

$kernel->bootstrap();

// Ruta del directorio donde están las imágenes originales
$originalImagesDir = storage_path('app/public/original_images/');

// Ruta del directorio donde se guardarán las imágenes renombradas
$storageDir = storage_path('app/public/');

// Generar nombres de imágenes del 1 al 42
for ($i = 1; $i <= 42; $i++) {
    $originalImagePath = $originalImagesDir . "image{$i}.webp";
    if (file_exists($originalImagePath)) {
        $timestamp = time();
        $uniqueId = uniqid();
        $newImageName = "post_{$timestamp}_{$uniqueId}.webp";
        $newImagePath = $storageDir . $newImageName;

        if (!copy($originalImagePath, $newImagePath)) {
            echo "Failed to copy {$originalImagePath}...\n";
        } else {
            echo "Copied {$originalImagePath} to {$newImagePath}\n";
        }
    } else {
        echo "Original image {$originalImagePath} does not exist.\n";
    }
}
