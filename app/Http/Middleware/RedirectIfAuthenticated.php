<?php

namespace App\Http\Middleware;

use App\Services\Auth\HomeRedirectService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    public function __construct(private readonly HomeRedirectService $homeRedirectService)
    {
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $path = $this->homeRedirectService->resolveHomePath(Auth::guard($guard)->user());

                return redirect('/' . ltrim($path, '/'));
            }
        }

        return $next($request);
    }
}
