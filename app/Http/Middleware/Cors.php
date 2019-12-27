<?php
namespace App\Http\Middleware;

class Cors
{
    public function handle($request, \Closure $next)
    {
        $headers = [
            'Access-Control-Allow-Origin' => env('ACCESS_CONTROL_ALLOW_ORIGIN', '*'),
            'Access-Control-Allow-Methods' => env('ACCESS_CONTROL_ALLOW_METHODS', 'GET, POST, PUT, DELETE, OPTIONS'),
            'Access-Control-Max-Age' => env('ACCESS_CONTROL_MAX_AGE', '86400'),
            'Access-Control-Allow-Headers' => env('ACCESS_CONTROL_ALLOW_HEADER', 'Content-Type, Authorization, X-Requested-With'),
        ];

        if ($request->isMethod('OPTIONS')) {
            return response()->json([], 200, $headers);
        }

        $response = $next($request);

        foreach ($headers as $field => $value) {
            $response->header($field, $value);
        }

        return $response;
    }
}
