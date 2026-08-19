<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureInternalQmsUser
{
    public function handle(Request $request, Closure $next): Response
    {
        abort_if($request->user()?->qms_role === 'Reporter', 403, 'Reporter users must use the Reporter portal.');

        return $next($request);
    }
}
