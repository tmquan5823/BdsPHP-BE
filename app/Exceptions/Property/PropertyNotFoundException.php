<?php

namespace App\Exceptions\Property;

use Exception;

class PropertyNotFoundException extends Exception
{
    protected $code = 404;
    protected $message = 'Không tìm thấy bất động sản.';

    public function __construct(string $message = '', int $code = 0, Exception $previous = null)
    {
        if (empty($message)) {
            $message = $this->message;
        }

        if ($code === 0) {
            $code = $this->getCode();
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
            'error' => 'Property Not Found',
            'message' => $this->getMessage(),
            'code' => $this->getCode(),
        ], $this->getCode());
    }
}
