<?php

namespace App\Services;

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
}
