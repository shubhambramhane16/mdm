<?php

namespace App\Http\Middleware\API;

use Closure;
use Response;
use Tymon\JWTAuth\Facades\JWTAuth;
use Config;

class JWTAuthorization
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
            if ($request->header('Authorization')) {

                $decodedToken = JWTAuth::parseToken()->authenticate();
                if ($decodedToken) {
                    return $next($request);
                } else {
                    $result['Success'] = 'False';
                    $result['Message'] = 'Authorization Token is missing or invalid in headers.';
                    return  response()->json($result, 401);
                }
            } else {
                $result['Success'] = 'False';
                $result['Message'] = 'Authorization Token is missing in headers.';
                return response()->json($result, 401);
            }
        } catch (\Exception $e) {
            // dd($e);
            $result['Result'] = [];
            $result['Success'] = 'False';
            $result['Message'] = $e->getMessage();
            return response()->json($result, 401);
        }
    }
}
