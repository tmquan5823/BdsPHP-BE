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
}
