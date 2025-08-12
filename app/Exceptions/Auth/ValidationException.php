<?php

namespace App\Exceptions\Auth;

use Exception;

class ValidationException extends Exception
{
    protected $code = 422;
    protected $message = 'Dữ liệu không hợp lệ.';
    protected $errors = [];

    public function __construct(string $message = '', array $errors = [], int $code = 0, Exception $previous = null)
    {
        if (empty($message)) {
            $message = $this->message;
        }

        if ($code === 0) {
            $code = $this->code;
        }

        $this->errors = $errors;

        parent::__construct($message, $code, $previous);
    }

    /**
     * Get validation errors
     *
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * Render the exception into an HTTP response.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function render($request)
    {
        return response()->json([
            'error' => 'Validation Failed',
            'message' => $this->getMessage(),
            'errors' => $this->getErrors(),
            'code' => $this->getCode()
        ], $this->getCode());
    }
}
