<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class RoleValidate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = User::with('roles')->find(Auth::id());
        $roles = collect($roles);
        $role_exist = $user->roles->filter(function ($value) use($roles) {
            return $roles->contains($value->slug);
        });

        if($role_exist->count() == 0){
            return response()->json(["message" => "Unauthorized"], 403);
        }

        return $next($request);
    }
}
