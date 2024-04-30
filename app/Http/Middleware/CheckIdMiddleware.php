<?php
namespace App\Http\Middleware;

use Closure;

class CheckIdMiddleware
{
    public function handle($request, Closure $next)
    {
        if (auth()->id() !== 228343232520519680) {
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}
?>