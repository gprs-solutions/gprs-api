<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Cors
{
    /**
     * CORS headers.
     */
    private const CORS_HEADERS = [
        'Access-Control-Allow-Origin'      => '*',
        'Access-Control-Allow-Methods'     => 'POST, GET, OPTIONS, PUT, DELETE',
        'Access-Control-Allow-Credentials' => 'true',
        'Access-Control-Max-Age'           => '86400',
        'Access-Control-Allow-Headers'     => 'Origin, Content-Type, '
            . 'Authorization, Content-Length, X-Requested-With, Host, '
            . 'Accept-Encoding, Referer, Accept, Content-Disposition, '
            . 'Content-Range, Content-Disposition, Content-Description, '
            . 'Accept-Language',
        'Access-Control-Expose-Headers'    => 'Authorization',
    ];

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request Request
     * @param \Closure                 $next    Next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->isMethod('OPTIONS')) {
            return response()->json('{"method":"OPTIONS"}', Response::HTTP_OK, self::CORS_HEADERS);
        }

        // Add cors headers.
        $response = $next($request);
        foreach (self::CORS_HEADERS as $key => $value) {
            $response->header($key, $value);
        }

        return $response;
    }
}
