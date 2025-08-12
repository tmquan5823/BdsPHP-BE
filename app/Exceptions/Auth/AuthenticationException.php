<?php

namespace App\Exceptions\Auth;

use Exception;

class AuthenticationException extends Exception
{
    protected $code = 401;
    protected $message = 'Thông tin đăng nhập không chính xác.';

    public function __construct(string $message = '', int $code = 0, Exception $previous = null)
    {
        if (empty($message)) {
            $message = $this->message;
        }

        if ($code === 0) {
            $code = $this->code;
        }

        parent::__construct($message, $code, $previous);
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
            'error' => 'Authentication Failed',
            'message' => $this->getMessage(),
            'code' => $this->getCode(),
        ], $this->getCode());
    }
}
