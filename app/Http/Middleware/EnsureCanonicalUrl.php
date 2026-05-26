<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureCanonicalUrl
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->isMethod('GET') && ! $request->isMethod('HEAD')) {
            return $next($request);
        }

        $configuredUrl = (string) config('app.url');
        $parsedCanonicalUrl = parse_url($configuredUrl);

        if (! is_array($parsedCanonicalUrl)) {
            return $next($request);
        }

        $canonicalScheme = strtolower((string) ($parsedCanonicalUrl['scheme'] ?? ''));
        $canonicalHost = strtolower((string) ($parsedCanonicalUrl['host'] ?? ''));
        $canonicalPort = isset($parsedCanonicalUrl['port']) ? (int) $parsedCanonicalUrl['port'] : null;

        if ($canonicalScheme === '' || $canonicalHost === '') {
            return $next($request);
        }

        $requestScheme = strtolower($request->getScheme());
        $requestHost = strtolower($request->getHost());
        $requestPath = $request->getPathInfo();
        $normalizedPath = $this->normalizePath($requestPath);

        $isSchemeDifferent = $requestScheme !== $canonicalScheme;
        $isHostDifferent = $requestHost !== $canonicalHost;
        $isPathDifferent = $requestPath !== $normalizedPath;
        if ($isSchemeDifferent || $isHostDifferent || $isPathDifferent) {
            return redirect()->to($this->buildCanonicalUrl($canonicalScheme, $canonicalHost, $canonicalPort, $normalizedPath, $request->getQueryString()), 301);
        }

        $response = $next($request);

        $robots = $this->resolveRobotsDirective($request);

        if ($robots !== null) {
            $response->headers->set('X-Robots-Tag', $robots);
        }

        return $response;
    }

    private function normalizePath(string $path): string
    {
        $collapsedPath = preg_replace('#/+#', '/', $path);
        $normalizedPath = is_string($collapsedPath) ? $collapsedPath : $path;

        if ($normalizedPath === '' || $normalizedPath === '/') {
            return '/';
        }

        return rtrim($normalizedPath, '/');
    }

    private function buildCanonicalUrl(
        string $scheme,
        string $host,
        ?int $port,
        string $path,
        ?string $queryString
    ): string {
        $authority = $host;

        if ($port !== null && ! $this->isDefaultPort($scheme, $port)) {
            $authority .= ':'.$port;
        }

        $url = $scheme.'://'.$authority.$path;

        if (is_string($queryString) && $queryString !== '') {
            $url .= '?'.$queryString;
        }

        return $url;
    }

    private function isDefaultPort(string $scheme, int $port): bool
    {
        return ($scheme === 'http' && $port === 80) || ($scheme === 'https' && $port === 443);
    }

    private function resolveRobotsDirective(Request $request): ?string
    {
        if ($request->is('blog/create')) {
            return 'noindex, nofollow';
        }

        if ($request->is('tracking') && $request->query->has('tracking_number')) {
            return 'noindex, follow';
        }

        return null;
    }
}
