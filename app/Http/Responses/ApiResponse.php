<?php

namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;

class ApiResponse
{
    public static function success($data = null, string $message = 'Success', int $status = 200): JsonResponse
    {
        $payload = [
            'success' => true,
            'message' => $message,
        ];

        if ($data !== null) {
            $payload['data'] = $data;
        }

        return response()->json($payload, $status);
    }

    public static function error(string $message = 'Error', int $status = 500, ?array $errors = null, ?string $reference = null): JsonResponse
    {
        $payload = [
            'success' => false,
            'message' => $message,
        ];

        if ($errors !== null) {
            $payload['errors'] = $errors;
        }

        if ($reference !== null) {
            $payload['reference'] = $reference;
        }

        return response()->json($payload, $status);
    }

    public static function validation(array $errors, string $message = 'Validation failed', int $status = 422): JsonResponse
    {
        return self::error($message, $status, $errors);
    }

    public static function notFound(string $message = 'Resource not found', int $status = 404): JsonResponse
    {
        return self::error($message, $status);
    }

    public static function unauthorized(string $message = 'Unauthenticated', int $status = 401): JsonResponse
    {
        return self::error($message, $status);
    }

    public static function serverError(string $message = 'An unexpected error occurred', int $status = 500, ?string $reference = null): JsonResponse
    {
        return self::error($message, $status, null, $reference);
    }
}
