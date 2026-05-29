<?php

namespace App\Exceptions;

use Exception;

class VehicleNotFoundException extends Exception
{
    public function render()
    {
        return response()->json([
            'status' => 'error',
            'code' => 404,
            'message' => 'Vehicle not found',
        ], 404);
    }
}

class UnauthorizedActionException extends Exception
{
    public function render()
    {
        return response()->json([
            'status' => 'error',
            'code' => 403,
            'message' => 'You are not authorized to perform this action',
        ], 403);
    }
}

class InvalidImageException extends Exception
{
    public function render()
    {
        return response()->json([
            'status' => 'error',
            'code' => 422,
            'message' => 'Invalid image file. Must be PNG, JPG, or WEBP (max 2MB)',
        ], 422);
    }
}

class InquiryNotFoundException extends Exception
{
    public function render()
    {
        return response()->json([
            'status' => 'error',
            'code' => 404,
            'message' => 'Inquiry not found',
        ], 404);
    }
}

class BuyerProfileException extends Exception
{
    public function render()
    {
        return response()->json([
            'status' => 'error',
            'code' => 400,
            'message' => 'Failed to create or retrieve buyer profile',
        ], 400);
    }
}
