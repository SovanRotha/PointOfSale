<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

abstract class Controller
{
    protected function handleException(\Throwable $e): JsonResponse
    {
        if ($e instanceof ValidationException) {
            return new JsonResponse([
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        }

        if ($e instanceof ModelNotFoundException) {
            return new JsonResponse([
                'message' => 'Resource not found',
            ], 404);
        }

        return new JsonResponse([
            'message' => 'Something went wrong',
            'error' => $e->getMessage(),
        ], 500);
    }
}
