<?php

namespace App\Http\Middleware\API;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSession
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {

        $message = 'You do not have permission to access this page. Kindly contact administrator.';
        $uriSegments = request()->segments();
        $roleId = auth()->user()->role_id;

        $systemRoles = getSystemRoles($roleId);

        if ($systemRoles == null) {
            return response()->json(['message' => $message], 403);
        }

        $systemRolesArray = json_decode($systemRoles->permission, true);
        $permission = 0;
        // dd($uriSegments[1]);
        if (isset($systemRolesArray[$uriSegments[1]])) {
            $permission = $systemRolesArray[$uriSegments[1]];
            if ($permission == 5) {
                return $next($request);
            } elseif ($permission == 4) {
                if (isset($uriSegments[2])) {
                    if ($uriSegments[2] == 'list' || $uriSegments[2] == 'create' || $uriSegments[2] == 'edit' || $uriSegments[1] == 'dashboard') {
                        return $next($request);
                    } else {
                        return response()->json(['message' => $message], 403);
                    }
                } else {
                    return $next($request);
                }
            } elseif ($permission == 3) {
                if (isset($uriSegments[2])) {
                    if ($uriSegments[2] == 'list' || $uriSegments[2] == 'create' || $uriSegments[1] == 'dashboard') {
                        return $next($request);
                    } else {
                        return response()->json(['message' => $message], 403);
                    }
                } else {
                    return $next($request);
                }
            } elseif ($permission == 2) {
                if (isset($uriSegments[2])) {
                    if ($uriSegments[2] == 'list' || $uriSegments[2] == 'create' || $uriSegments[1] == 'dashboard') {
                        return $next($request);
                    } else {
                        return response()->json(['message' => $message], 403);
                    }
                } else {
                    return $next($request);
                }
            } elseif ($permission == 1) {
                if (isset($uriSegments[2])) {
                    if ($uriSegments[2] == 'list' || $uriSegments[1] == 'dashboard') {
                        return $next($request);
                    } else {
                        return response()->json(['message' => $message], 403);
                    }
                } else {
                    return $next($request);
                }
            } else {
                // dd($uriSegments);
                if ($uriSegments[2] == 'dashboard') {
                    return $next($request);
                } else {
                    return response()->json(['message' => $message], 403);
                }
            }
        } else {
            if ($uriSegments[1] == 'dashboard') {
                return $next($request);
            } else {
                // Auth::logout();
                // Session::flush();
                return response()->json(['message' => $message], 403);
            }
        }

    }
}
