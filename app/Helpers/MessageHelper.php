<?php

namespace App\Helpers;

class MessageHelper
{
    public int $StatusCode;
    public string $Message;

    public function __construct(int $statusCode = 200, string $message = '')
    {
        $this->StatusCode = $statusCode;
        $this->Message = $message;
    }

    /**
     * Create a success response
     *
     * @param string $message
     * @param mixed $data
     * @return array
     */
    public static function success(string $message = 'Success', $data = null): array
    {
        $response = [
            'StatusCode' => 200,
            'Message' => $message,
            'Success' => true
        ];

        if ($data !== null) {
            $response['Data'] = $data;
        }

        return $response;
    }

    /**
     * Create an error response
     *
     * @param string $message
     * @param int $statusCode
     * @param mixed $errors
     * @return array
     */
    public static function error(string $message = 'Error occurred', int $statusCode = 500, $errors = null): array
    {
        $response = [
            'StatusCode' => $statusCode,
            'Message' => $message,
            'Success' => false
        ];

        if ($errors !== null) {
            $response['Errors'] = $errors;
        }

        return $response;
    }

    /**
     * Create a validation error response
     *
     * @param array $errors
     * @param string $message
     * @return array
     */
    public static function validationError(array $errors, string $message = 'Validation failed'): array
    {
        return [
            'StatusCode' => 422,
            'Message' => $message,
            'Success' => false,
            'Errors' => $errors
        ];
    }

    /**
     * Create an unauthorized response
     *
     * @param string $message
     * @return array
     */
    public static function unauthorized(string $message = 'Unauthorized'): array
    {
        return [
            'StatusCode' => 401,
            'Message' => $message,
            'Success' => false
        ];
    }

    /**
     * Create a not found response
     *
     * @param string $message
     * @return array
     */
    public static function notFound(string $message = 'Resource not found'): array
    {
        return [
            'StatusCode' => 404,
            'Message' => $message,
            'Success' => false
        ];
    }

    /**
     * Convert to array
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'StatusCode' => $this->StatusCode,
            'Message' => $this->Message
        ];
    }
}
