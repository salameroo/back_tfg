<?php

namespace App\Http\Middleware;

use Closure;

class Cors
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        // Allow specific origin
        $response->headers->set('Access-Control-Allow-Origin', 'https://www.cargram.asalamero.dawmor.cloud');
        $response->headers->set('Access-Control-Allow-Methods', 'POST, GET, OPTIONS, PUT, DELETE');
        $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With');
        $response->headers->set('Access-Control-Allow-Credentials', 'true');

        if ($request->getMethod() === 'OPTIONS') {
            return response('', 204)->withHeaders([
                'Access-Control-Allow-Origin' => 'https://www.cargram.asalamero.dawmor.cloud',
                'Access-Control-Allow-Methods' => 'POST, GET, OPTIONS, PUT, DELETE',
                'Access-Control-Allow-Headers' => 'Content-Type, Authorization, X-Requested-With',
                'Access-Control-Allow-Credentials' => 'true',
            ]);
        }

        return $response;
    }
}
