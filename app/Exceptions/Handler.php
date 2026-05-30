<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Throwable;
use App\Http\Responses\ApiResponse;
use App\Models\ActivityLog;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->renderable(function (AuthenticationException $e, Request $request) {
            if ($request->expectsJson()) {
                return ApiResponse::unauthorized('Unauthenticated.', 401);
            }

            return redirect()->guest(route('login'));
        });

        $this->renderable(function (TokenMismatchException $e, Request $request) {
            if ($request->expectsJson()) {
                return ApiResponse::error('Session expired. Please refresh and try again.', 419);
            }

            if ($request->hasSession()) {
                $request->session()->invalidate();
                $request->session()->regenerateToken();
            }

            return redirect()
                ->route('login')
                ->with('error', 'Session expired. Please sign in again.');
        });

        $this->renderable(function (QueryException $e, Request $request) {
            if (! $this->isDatabaseConnectionException($e)) {
                return null;
            }

            $reference = $this->logServerError($e, 503, 'Database connection failed');
            $message = 'Database service is temporarily unavailable. Please try again later.';

            if ($request->expectsJson()) {
                return ApiResponse::error($message, 503, null, $reference);
            }

            return $this->renderErrorView($request, 503, $reference, $message);
        });

        $this->renderable(function (Throwable $e, Request $request) {
            $status = 500;

            if ($e instanceof HttpExceptionInterface) {
                $status = $e->getStatusCode();
            }

            if ($status < 500) {
                if ($request->expectsJson()) {
                    return null;
                }

                return $this->renderErrorView($request, $status, null);
            }

            $reference = $this->logServerError($e, $status);
            $message = $status === 503
                ? 'Database service is temporarily unavailable. Please try again later.'
                : 'An unexpected error occurred.';

            if ($request->expectsJson()) {
                return ApiResponse::serverError($message, $status, $reference);
            }

            return $this->renderErrorView($request, $status, $reference);
        });
    }

    private function logServerError(Throwable $e, int $status, ?string $context = null): string
    {
        $reference = Str::upper(Str::substr((string) Str::uuid(), 0, 8));

        Log::error(($context ?? 'Unhandled exception') . " [{$reference}] (status: {$status}): " . $e->getMessage(), [
            'exception' => $e,
            'status' => $status,
            'reference' => $reference,
        ]);

        try {
            ActivityLog::logActivity(
                auth()->id() ?? null,
                'exception',
                null,
                null,
                ($context ?? 'Unhandled exception') . " [{$reference}] " . $e->getMessage()
            );
        } catch (Throwable $ex) {
            // Avoid throwing while logging
            Log::warning('Failed to write to activity_logs: ' . $ex->getMessage());
        }

        return $reference;
    }

    private function renderErrorView(Request $request, int $status, ?string $reference = null, ?string $message = null)
    {
        $view = view()->exists("errors.{$status}") ? "errors.{$status}" : 'errors.500';
        $data = array_filter([
            'reference' => $reference,
            'errorMessage' => $message,
        ], static fn ($value) => $value !== null && $value !== '');

        return response()->view($view, $data, $status);
    }

    private function isDatabaseConnectionException(QueryException $e): bool
    {
        $message = $e->getMessage();

        foreach ([
            'SQLSTATE[HY000] [2002]',
            'SQLSTATE[HY000] [2003]',
            'SQLSTATE[HY000] [2006]',
            'SQLSTATE[HY000] [1045]',
            'Connection refused',
            'No such file or directory',
            'php_network_getaddresses',
        ] as $needle) {
            if (str_contains($message, $needle)) {
                return true;
            }
        }

        return false;
    }
}
