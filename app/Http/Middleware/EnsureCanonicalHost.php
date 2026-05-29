<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureCanonicalHost
{
    public function handle(Request $request, Closure $next): Response
    {
        $appUrl = (string) config('app.url');
        $appHost = parse_url($appUrl, PHP_URL_HOST);

        // Skip if APP_URL host is unavailable or already matches current host.
        if (empty($appHost) || $request->getHost() === $appHost) {
            return $next($request);
        }

        $appScheme = parse_url($appUrl, PHP_URL_SCHEME) ?: $request->getScheme();
        $appPort = parse_url($appUrl, PHP_URL_PORT);
        $portSegment = $appPort ? ':' . $appPort : '';

        $target = sprintf(
            '%s://%s%s%s',
            $appScheme,
            $appHost,
            $portSegment,
            $request->getRequestUri()
        );

        return redirect()->to($target, 302);
    }
}
