<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectingToRoleDashboard
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
			$user = auth()->user();

			if ($user->hasRole('admin') && !$request->is('admin/*')) {
        return redirect()->route('admin.dashboard');
    }

		if ($user->hasRole('spv') && !$request->is('spv/*')) {
        return redirect()->route('spv.dashboard');
    }

		if ($user->hasRole('staf') && !$request->is('/*')) {
        return redirect()->route('staff');
    }
        return $next($request);
    }
}
