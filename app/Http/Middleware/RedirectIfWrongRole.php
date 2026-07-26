<?php
// app/Http/Middleware/RedirectIfWrongRole.php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RedirectIfWrongRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!$request->user()) {
            return redirect()->route('login');
        }

        if (!$request->user()->hasAnyRole($roles)) {
            // redirect ke dashboard sesuai role user yang sebenarnya
            return redirect()->route($request->user()->dashboardRoute())
                ->with('warning', 'Kamu tidak memiliki akses ke halaman tersebut.');
        }

        return $next($request);
    }
}
