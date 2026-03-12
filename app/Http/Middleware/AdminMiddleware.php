<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth('admin')->check()) {
            return redirect('/admin/login')->withErrors(['email' => 'Please log in to access the admin panel.']);
        }
        return $next($request);
    }
}
