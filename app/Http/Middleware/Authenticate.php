<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Closure;
use App\Models\User;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */

    public function handle($request, Closure $next)
    {
        // Perform action
        //dd($request->headers['authorization']);
        $authorization = $request->headers->get('authorization');
        $authorization = explode(' ', $authorization);
        $token = $authorization[1] ?? "abc";
        $time = time();

        $user = User::query()->where('user_token', $token)->first();
        if($user){
            if($user->user_token_expired > $time ){
                return $next($request);
            }else{
                return response()->json(['message' => 'Token has expired'], 400);
            }
        }else{
            return response()->json(['message' => 'Token not found'], 400);
        }  
    }
}
