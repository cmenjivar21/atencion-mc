<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Permission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, String $permission)
    {
        $url = explode('/',  $request->getPathInfo());
        //dd($url);
        if (!auth()->user()->hasPermissionTo($permission)) {

            if ($url[1] == "api") {
                return response()->json([
                    'success' => true,
                    'message' => 'El usuario no tiene permisos para modificar.',
                    "type" => "fail"
                ]);
            }

            return abort(302);
        }

        return $next($request);
    }
}
