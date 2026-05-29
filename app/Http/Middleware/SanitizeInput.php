<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use HTMLPurifier;
use HTMLPurifier_Config;

class SanitizeInput
{
    /**
     * List of fields to skip sanitization.
     */
    protected array $skipFields = [
        'password',
        'password_confirmation',
        'token',
        'csrf_token',
        '_token',
        'api_token',
        'remember_token',
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->isMethod('post') || $request->isMethod('put') || $request->isMethod('patch')) {
            $this->sanitize($request);
        }

        return $next($request);
    }

    /**
     * Sanitize all request input.
     */
    protected function sanitize(Request $request): void
    {
        $all = $request->all();

        foreach ($all as $key => $value) {
            if (in_array($key, $this->skipFields)) {
                continue;
            }

            if (is_string($value)) {
                $all[$key] = $this->cleanInput($value);
            } elseif (is_array($value)) {
                $all[$key] = $this->sanitizeArray($value);
            }
        }

        $request->merge($all);
    }

    /**
     * Sanitize array recursively.
     */
    protected function sanitizeArray(array $data): array
    {
        $sanitized = [];

        foreach ($data as $key => $value) {
            if (in_array($key, $this->skipFields)) {
                $sanitized[$key] = $value;
                continue;
            }

            if (is_string($value)) {
                $sanitized[$key] = $this->cleanInput($value);
            } elseif (is_array($value)) {
                $sanitized[$key] = $this->sanitizeArray($value);
            } else {
                $sanitized[$key] = $value;
            }
        }

        return $sanitized;
    }

    /**
     * Clean individual input string.
     */
    protected function cleanInput(string $input): string
    {
        // Remove null bytes
        $input = str_replace("\0", '', $input);

        // Escape HTML entities by default
        $input = htmlspecialchars($input, ENT_QUOTES, 'UTF-8');

        // Allow specific HTML tags for rich text fields
        $allowedTags = '<p><br><strong><b><em><i><u><ul><ol><li><a><blockquote>';
        $input = strip_tags($input, $allowedTags);

        return $input;
    }
}
