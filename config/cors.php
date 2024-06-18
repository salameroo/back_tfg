<?php
return [
    'paths' => ['api/*'],
    'allowed_methods' => ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS'], // Métodos permitidos
    'allowed_origins' => ['https://www.cargram.asalamero.dawmor.cloud'], // Tu dominio frontend
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['Content-Type', 'X-Requested-With', 'Authorization', 'Accept', 'Origin'], // Headers permitidos
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => true, // Asegúrate de que esto esté en true
];
