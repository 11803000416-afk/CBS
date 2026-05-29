<?php

namespace App\Services;

class ApiResponseService
{
    /**
     * Success Response
     */
    public static function success($data = null, $message = 'Success', $statusCode = 200)
    {
        return response()->json([
            'status' => 'success',
            'code' => $statusCode,
            'message' => $message,
            'data' => $data,
            'timestamp' => now()->toIso8601String(),
        ], $statusCode);
    }

    /**
     * Error Response
     */
    public static function error($message = 'An error occurred', $statusCode = 400, $errors = null)
    {
        return response()->json([
            'status' => 'error',
            'code' => $statusCode,
            'message' => $message,
            'errors' => $errors,
            'timestamp' => now()->toIso8601String(),
        ], $statusCode);
    }

    /**
     * Paginated Response
     */
    public static function paginated($paginator, $message = 'Data retrieved successfully')
    {
        return response()->json([
            'status' => 'success',
            'code' => 200,
            'message' => $message,
            'data' => $paginator->items(),
            'pagination' => [
                'total' => $paginator->total(),
                'per_page' => $paginator->perPage(),
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'from' => $paginator->firstItem(),
                'to' => $paginator->lastItem(),
            ],
            'timestamp' => now()->toIso8601String(),
        ], 200);
    }

    /**
     * Validation Error Response
     */
    public static function validationError($errors, $message = 'Validation failed')
    {
        return response()->json([
            'status' => 'validation_error',
            'code' => 422,
            'message' => $message,
            'errors' => $errors,
            'timestamp' => now()->toIso8601String(),
        ], 422);
    }

    /**
     * Not Found Response
     */
    public static function notFound($message = 'Resource not found')
    {
        return response()->json([
            'status' => 'error',
            'code' => 404,
            'message' => $message,
            'timestamp' => now()->toIso8601String(),
        ], 404);
    }

    /**
     * Unauthorized Response
     */
    public static function unauthorized($message = 'Unauthorized access')
    {
        return response()->json([
            'status' => 'error',
            'code' => 401,
            'message' => $message,
            'timestamp' => now()->toIso8601String(),
        ], 401);
    }

    /**
     * Forbidden Response
     */
    public static function forbidden($message = 'Forbidden access')
    {
        return response()->json([
            'status' => 'error',
            'code' => 403,
            'message' => $message,
            'timestamp' => now()->toIso8601String(),
        ], 403);
    }
}
