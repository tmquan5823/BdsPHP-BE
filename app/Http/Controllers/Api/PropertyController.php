<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\PropertyService;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    protected $propertyService;

    public function __construct(PropertyService $propertyService)
    {
        $this->propertyService = $propertyService;
    }

    /**
     * Helper method để tạo response format nhất quán
     */
    private function response($status, $message, $code, $data = null)
    {
        return response()->json([
            'status' => $status,
            'message' => $message,
            'code' => $code,
            'data' => $data,
        ], $code);
    }

    /**
     * Get property list
     */
    public function getPropertyList(Request $request)
    {
        try {
            $result = $this->propertyService->getPropertyList($request);

            return $this->response('success', 'Lấy danh sách bất động sản thành công', 200, $result);
        } catch (\Exception $e) {
            return $this->response('error', 'Không thể lấy danh sách bất động sản', 500, ['message' => $e->getMessage()]);
        }
    }

    /**
     * Get property detail
     */
    public function getPropertyDetail(Request $request, $id)
    {
        try {
            $result = $this->propertyService->getPropertyDetail($id);

            return $this->response('success', 'Lấy chi tiết bất động sản thành công', 200, $result);
        } catch (\Exception $e) {
            return $this->response('error', 'Không thể lấy chi tiết bất động sản', 500, ['message' => $e->getMessage()]);
        }
    }
}
