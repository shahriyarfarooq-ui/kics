<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePortalRole
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user()) {
            return $next($request);
        }

        $path = trim($request->path(), '/');
        $adminArea = $path === 'admin-page'
            || str_starts_with($path, 'admin/')
            || $path === 'projects'
            || str_starts_with($path, 'projects/')
            || (in_array($path, ['menu-sections', 'menu-links'], true) && ! $request->isMethod('GET'));

        if ($adminArea && $request->user()->role !== 'admin') {
            abort(403);
        }

        if (str_starts_with($path, 'staff/profile') && $request->user()->role !== 'staff') {
            abort(403);
        }

        return $next($request);
    }
}
