<?php

namespace App\Repositories;

use App\Models\Property;

class PropertyRepository extends BaseRepository
{
    /**
     * Get the model for the repository
     *
     * @return string
     */
    public function getModel(): string
    {
        return Property::class;
    }

    /**
     * Get property list with pagination and images
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function getPropertyList($request): array
    {
        $query = Property::with([
            'images' => function ($query) {
                $query->select('id', 'property_id', 'image_path', 'is_primary')
                    ->orderBy('is_primary', 'desc')
                    ->orderBy('sort_order', 'asc');
            },
        ]);

        // Apply filters if provided
        if ($request->has('city') && $request->city) {
            $query->where('city', $request->city);
        }

        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        if ($request->has('property_type') && $request->property_type) {
            $query->where('property_type', $request->property_type);
        }

        if ($request->has('min_price') && $request->min_price) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->has('max_price') && $request->max_price) {
            $query->where('price', '<=', $request->max_price);
        }

        // Get paginated results
        $perPage = $request->get('per_page', 10);
        $properties = $query->orderBy('created_at', 'desc')
            ->paginate($perPage);

        // Check if there are any properties
        if ($properties->isEmpty()) {
            return [
                'data' => [],
                'meta' => [
                    'current_page' => $properties->currentPage(),
                    'last_page' => $properties->lastPage(),
                    'total' => $properties->total(),
                ],
            ];
        }

        // Format the response
        $formattedProperties = $properties->getCollection()->map(function ($property) {
            $images = [];
            if ($property->images && $property->images->isNotEmpty()) {
                $images = $property->images->map(function ($image) {
                    return [
                        'id' => $image->id,
                        'image_path' => $image->image_path,
                        'is_primary' => (bool) $image->is_primary,
                    ];
                })->toArray();
            }

            return [
                'id' => $property->id,
                'title' => $property->title,
                'price' => (int) $property->price,
                'city' => $property->city,
                'status' => $property->status,
                'images' => $images,
            ];
        })->toArray();

        return [
            'data' => $formattedProperties,
            'meta' => [
                'current_page' => $properties->currentPage(),
                'last_page' => $properties->lastPage(),
                'total' => $properties->total(),
            ],
        ];
    }
}
