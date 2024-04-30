<?php
namespace App\Http\Middleware;

use Closure;

class RemoveTrailingSlash
{
    public function handle($request, Closure $next)
    {
        if (preg_match('/.+\/$/', $request->getRequestUri())) {
            return redirect(rtrim($request->getRequestUri(), '/'), 301);
        }

        return $next($request);
    }
}
