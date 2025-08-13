<?php

namespace App\Services;

use App\Exceptions\Property\PropertyNotFoundException;
use App\Repositories\PropertyRepository;
use Illuminate\Http\Request;

class PropertyService
{
    protected $propertyRepository;

    public function __construct(PropertyRepository $propertyRepository)
    {
        $this->propertyRepository = $propertyRepository;
    }

    /**
     * Get property list with pagination and filters
     *
     * @param Request $request
     * @return array
     */
    public function getPropertyList(Request $request): array
    {
        try {
            // Get properties from repository
            $result = $this->propertyRepository->getPropertyList($request);

            return $result;
        } catch (\Exception $e) {
            // Log error for debugging
            \Log::error('Property list error: ' . $e->getMessage());

            throw new \Exception('Không thể lấy danh sách bất động sản: ' . $e->getMessage());
        }
    }

    /**
     * Get property detail by ID
     *
     * @param int $id
     * @return object|null
     */
    public function getPropertyDetail(int $id): ?object
    {
        try {
            // Get property detail from repository
            $result = $this->propertyRepository->getPropertyDetail($id);

            if (! $result) {
                throw new PropertyNotFoundException('Không tìm thấy bất động sản');
            }

            return $result;
        } catch (\Exception $e) {
            // Log error for debugging
            \Log::error('Property detail error: ' . $e->getMessage());

            throw new \Exception('Không thể lấy chi tiết bất động sản: ' . $e->getMessage());
        }
    }

    /**
     * Create new property
     *
     * @param array $data
     * @return object
     */
    public function createProperty(array $data): object
    {
        try {
            // Create property via repository
            $result = $this->propertyRepository->createProperty($data);

            return $result;
        } catch (\Exception $e) {
            // Log error for debugging
            \Log::error('Property creation error: ' . $e->getMessage());

            throw new \Exception('Không thể tạo bất động sản: ' . $e->getMessage());
        }
    }

    /**
     * Update property by ID
     *
     * @param int $id
     * @param array $data
     * @return object|null
     */
    public function updateProperty(int $id, array $data): ?object
    {
        try {
            // Update property via repository
            $result = $this->propertyRepository->updateProperty($id, $data);

            return $result;
        } catch (\Exception $e) {
            // Log error for debugging
            \Log::error('Property update error: ' . $e->getMessage());

            throw new \Exception('Không thể cập nhật bất động sản: ' . $e->getMessage());
        }
    }

    /**
     * Delete property by ID
     *
     * @param int $id
     * @return bool
     */
    public function deleteProperty(int $id): bool
    {
        try {
            $result = $this->propertyRepository->deleteProperty($id);

            return $result;
        } catch (\Exception $e) {
            \Log::error('Property deletion error: ' . $e->getMessage());

            throw new \Exception('Không thể xóa bất động sản: ' . $e->getMessage());
        }
    }

    /**
     * Upload additional images for property
     *
     * @param int $id
     * @param array $images
     * @return object|null
     */
    public function uploadImages(int $id, array $images): ?object
    {
        try {
            // Upload images via repository
            $result = $this->propertyRepository->uploadImages($id, $images);

            return $result;
        } catch (\Exception $e) {
            // Log error for debugging
            \Log::error('Lỗi khi tải ảnh: ' . $e->getMessage());

            throw new \Exception('Không thể upload ảnh cho bất động sản: ' . $e->getMessage());
        }
    }
}
