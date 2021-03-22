<?php

namespace App\Http\Middleware;

use Closure;

class AdminCompany
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
        if(auth()->check() && auth()->user()->type == 'COMPANY') {
//            echo json_encode(auth()->user());
            return $next($request);
        } else {
            /*$response = [
                'user' => auth()->user(),
                'message' => "Access not allowed!",
                'status' => 403
            ];
            return response($response, 403);*/
            return redirect('/dashboard');
        }
    }
}
