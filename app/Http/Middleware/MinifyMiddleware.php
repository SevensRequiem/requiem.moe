<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;

class MinifyMiddleware {

    public function handle($request, Closure $next) {
        $response = $next($request);
        $buffer = $response->getContent();

        // remove comments
        $buffer = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $buffer);

        // remove tabs, spaces, newlines, etc.
        $buffer = str_replace(["\r\n", "\r", "\n", "\t", '  ', '    ', '    '], '', $buffer);

        // remove unnecessary spaces
        $buffer = str_replace(['> <', '>  <', '>   <'], '><', $buffer);

        $response->setContent($buffer);

        return $response;
    }

}