<?php

namespace WA\Http\Middleware;

class CorsMiddleware
{
    public function handle($request, \Closure $next)
    {
        $response = $next($request);
        $response->headers->set('Access-Control-Allow-Methods', 'HEAD, GET, POST, PUT, PATCH, DELETE');
        $response->headers->set('Access-Control-Allow-Credentials', 'true');
        $response->headers->set('Access-Control-Allow-Headers', $request->header('Access-Control-Request-Headers'));
        $response->headers->set('Access-Control-Allow-Origin', env('FRONTEND_DOMAIN', '*'));

        return $response;
    }
}
