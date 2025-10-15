<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if admin is logged in via session
        if (!session()->has('admin_id')) {
            // If it's an AJAX request, return unauthorized
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['message' => 'Unauthorized'], 401);
            }
            
            // Store the intended URL so we can redirect back after login
            session()->put('url.intended', $request->fullUrl());
            
            // Redirect to admin login page
            return redirect()->route('admin.login')
                ->with('error', 'Please login to access admin panel');
        }

        return $next($request);
    }
}