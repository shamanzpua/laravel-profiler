<?php
namespace shamanzpua\LaravelProfiler\Middleware;

use Closure;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AuthCodeMiddleware
{
    const NAME = 'shamanzpua.laravel-profiler.auth-code';

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $authCode = config('code-profiler.auth_code');
        if (!$authCode) {
            return $next($request);
        }

        if ($request->get('code_auth') !== $authCode) {
            throw new NotFoundHttpException();
        }
        return $next($request);
    }
}