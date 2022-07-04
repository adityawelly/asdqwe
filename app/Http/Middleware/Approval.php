<?php

namespace App\Http\Middleware;

use Closure;

class Approval
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
        $is_superior = $request->user()->employee->isSuperior();

        if (!$is_superior) {
            abort(403);
        }
        return $next($request);
    }
}
