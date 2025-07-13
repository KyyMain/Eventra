<?php

namespace App\Helpers;

use CodeIgniter\HTTP\ResponseInterface;

class ApiResponseHelper
{
    /**
     * Success response
     */
    public static function success($data = null, string $message = 'Success', int $code = 200): ResponseInterface
    {
        $response = [
            'success' => true,
            'status' => 'success',
            'message' => $message,
            'code' => $code,
            'timestamp' => date('c'),
            'version' => '1.0'
        ];

        if ($data !== null) {
            $response['data'] = $data;
        }

        return service('response')
            ->setStatusCode($code)
            ->setJSON($response);
    }

    /**
     * Error response
     */
    public static function error(string $message = 'Error', int $code = 400, $errors = null): ResponseInterface
    {
        $response = [
            'success' => false,
            'status' => 'error',
            'message' => $message,
            'code' => $code,
            'timestamp' => date('c'),
            'version' => '1.0'
        ];

        if ($errors !== null) {
            $response['errors'] = $errors;
        }

        return service('response')
            ->setStatusCode($code)
            ->setJSON($response);
    }

    /**
     * Validation error response
     */
    public static function validationError(array $errors): ResponseInterface
    {
        return self::error('Validation failed', 422, $errors);
    }

    /**
     * Not found response
     */
    public static function notFound(string $message = 'Resource not found'): ResponseInterface
    {
        return self::error($message, 404);
    }

    /**
     * Unauthorized response
     */
    public static function unauthorized(string $message = 'Unauthorized'): ResponseInterface
    {
        return self::error($message, 401);
    }

    /**
     * Forbidden response
     */
    public static function forbidden(string $message = 'Forbidden'): ResponseInterface
    {
        return self::error($message, 403);
    }

    /**
     * Server error response
     */
    public static function serverError(string $message = 'Internal server error'): ResponseInterface
    {
        return self::error($message, 500);
    }

    /**
     * Paginated response
     */
    public static function paginated(array $data, array $pagination): ResponseInterface
    {
        return self::success([
            'items' => $data,
            'pagination' => $pagination
        ]);
    }

    /**
     * Created response
     */
    public static function created($data = null, string $message = 'Resource created successfully'): ResponseInterface
    {
        return self::success($data, $message, 201);
    }

    /**
     * No content response
     */
    public static function noContent(): ResponseInterface
    {
        return service('response')
            ->setStatusCode(204);
    }
}