<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;

class isAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!session()->has('userId')) {
            // Redirect to the login route
            return redirect()->route('login.get');
        }
        // Check if user with user id exists in database
        $user = User::where('id', $request->session()->get('userId'))->first();
        if (!$user) {return redirect()->route('login.get');}
        return $next($request);
    }
}
