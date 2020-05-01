<?php

namespace App\Http\Middleware;

use Closure;
use Exception;

class WebHookCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        try {

            //check if header misses something
            if (! $request->headers->has('Event') || ! $request->headers->has('Authorization')) {

                // Define default error message if header is misconstrued
                $invalidResponse = response()->json([
                    'status'    => 'error',
                    'error'     => 'header malformed',
                ], 417);

                return $invalidResponse;
            }

            //check if authorization is valid
            if (! in_array($request->header('Authorization'), config('authorizations.keys'))) {

                // Define default error message if request isn't authorized
                $invalidResponse = response()->json([
                    'status'    => 'error',
                    'message'     => 'not authorized',
                ], 401);

                return $invalidResponse;
            }

        } catch(Exception $exception) {

            return response()->json([
                'status'    => 'error',
                'message'   => $exception,
            ], 500);
        }

        return $next($request);
    }
}
