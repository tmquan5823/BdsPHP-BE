<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Validations\PropertyValidation;
use App\Services\PropertyService;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    protected $propertyService;
    protected $propertyValidation;

    public function __construct(PropertyService $propertyService, PropertyValidation $propertyValidation)
    {
        $this->propertyService = $propertyService;
        $this->propertyValidation = $propertyValidation;
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

    /**
     * Create new property
     */
    public function createProperty(Request $request)
    {
        try {
            $validator = $this->propertyValidation->validateCreateProperty($request);

            if ($validator->fails()) {
                return $this->response('error', 'Dữ liệu không hợp lệ', 422, ['errors' => $validator->errors()]);
            }

            $validated = $validator->validated();
            $result = $this->propertyService->createProperty($validated);

            return $this->response('success', 'Tạo bất động sản thành công', 201, $result);
        } catch (\Exception $e) {
            return $this->response('error', 'Không thể tạo bất động sản', 500, ['message' => $e->getMessage()]);
        }
    }

    /**
     * Update property
     */
    public function updateProperty(Request $request, $id)
    {
        try {
            $validator = $this->propertyValidation->validateUpdateProperty($request);

            if ($validator->fails()) {
                return $this->response('error', 'Dữ liệu không hợp lệ', 422, ['errors' => $validator->errors()]);
            }

            $validated = $validator->validated();
            $result = $this->propertyService->updateProperty($id, $validated);

            if (! $result) {
                return $this->response('error', 'Không tìm thấy bất động sản', 404);
            }

            return $this->response('success', 'Cập nhật bất động sản thành công', 200, $result);
        } catch (\Exception $e) {
            return $this->response('error', 'Không thể cập nhật bất động sản', 500, ['message' => $e->getMessage()]);
        }
    }

    /**
     * Delete property
     */
    public function deleteProperty(Request $request, $id)
    {
        try {
            $result = $this->propertyService->deleteProperty($id);

            if (! $result) {
                return $this->response('error', 'Không tìm thấy bất động sản', 404);
            }

            return $this->response('success', 'Xóa bất động sản thành công', 200);
        } catch (\Exception $e) {
            return $this->response('error', 'Không thể xóa bất động sản', 500, ['message' => $e->getMessage()]);
        }
    }

    /**
     * Upload additional images for property
     */
    public function uploadImages(Request $request, $id)
    {
        try {
            $validator = $this->propertyValidation->validateUploadImages($request);

            if ($validator->fails()) {
                return $this->response('error', 'Dữ liệu không hợp lệ', 422, ['errors' => $validator->errors()]);
            }

            $validated = $validator->validated();
            $result = $this->propertyService->uploadImages($id, $validated['images']);

            if (! $result) {
                return $this->response('error', 'Không tìm thấy bất động sản', 404);
            }

            return $this->response('success', 'Tải ảnh thành công', 200, $result);
        } catch (\Exception $e) {
            return $this->response('error', 'Không thể upload ảnh', 500, ['message' => $e->getMessage()]);
        }
    }
}
