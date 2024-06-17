<?php

namespace App\Http\Middleware;

use Closure;

class Cors
{
    protected $allowedOrigins = [
        'https://www.cargram.asalamero.dawmor.cloud/',
        'https://cargram.asalamero.dawmor.cloud/'
    ];

    public function handle($request, Closure $next)
    {
        $origin = $request->headers->get('origin');

        if (in_array($origin, $this->allowedOrigins)) {
            return $next($request)
                ->header('Access-Control-Allow-Origin', $origin)
                ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
                ->header('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With, X-CSRF-Token')
                ->header('Access-Control-Allow-Credentials', 'true');
        }

        return $next($request);
    }
}
