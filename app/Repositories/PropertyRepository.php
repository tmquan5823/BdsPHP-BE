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

    /**
     * Get property detail by ID with images
     * @param int $id
     * @return object|null
     */
    public function getPropertyDetail(int $id): ?object
    {
        $property = Property::with([
            'images' => function ($query) {
                $query->select('id', 'property_id', 'image_path', 'is_primary', 'sort_order')
                    ->orderBy('is_primary', 'desc')
                    ->orderBy('sort_order', 'asc');
            },
        ])
            ->select(
                'id',
                'title',
                'description',
                'property_type',
                'status',
                'price',
                'area',
                'bedrooms',
                'bathrooms',
                'floors',
                'address',
                'city',
                'district',
                'postal_code',
                'latitude',
                'longitude',
                'year_built',
                'features',
                'contact_name',
                'contact_phone',
                'contact_email',
                'created_at',
                'updated_at'
            )
            ->where('id', $id)
            ->first();

        if (! $property) {
            return null;
        }

        // Format images
        $formattedImages = [];
        if ($property->images && $property->images->isNotEmpty()) {
            $formattedImages = $property->images->map(function ($image) {
                return [
                    'id' => $image->id,
                    'image_path' => $image->image_path,
                    'is_primary' => (bool) $image->is_primary,
                    'sort_order' => $image->sort_order,
                ];
            })->toArray();
        }

        // Format property data
        $formattedProperty = [
            'id' => $property->id,
            'title' => $property->title,
            'description' => $property->description,
            'property_type' => $property->property_type,
            'status' => $property->status,
            'price' => (int) $property->price,
            'area' => (float) $property->area,
            'bedrooms' => $property->bedrooms,
            'bathrooms' => $property->bathrooms,
            'floors' => $property->floors,
            'address' => $property->address,
            'city' => $property->city,
            'district' => $property->district,
            'postal_code' => $property->postal_code,
            'location' => [
                'latitude' => (float) $property->latitude,
                'longitude' => (float) $property->longitude,
            ],
            'year_built' => $property->year_built,
            'features' => $property->features,
            'contact' => [
                'name' => $property->contact_name,
                'phone' => $property->contact_phone,
                'email' => $property->contact_email,
            ],
            'images' => $formattedImages,
            'created_at' => $property->created_at,
            'updated_at' => $property->updated_at,
        ];

        return (object) $formattedProperty;
    }

    /**
     * Create new property with images
     * @param array $data
     * @return object
     */
    public function createProperty(array $data): object
    {
        $images = $data['images'] ?? [];
        unset($data['images']);

        $property = Property::create($data);

        if (! empty($images)) {
            foreach ($images as $index => $uploadedFile) {
                // Generate unique filename
                $filename = time() . '_' . $index . '.' . $uploadedFile->getClientOriginalExtension();

                // Store file in storage/app/public/properties
                $path = $uploadedFile->storeAs('properties', $filename, 'public');

                // Create image record
                $property->images()->create([
                    'image_path' => '/storage/' . $path,
                    'image_name' => $uploadedFile->getClientOriginalName(),
                    'is_primary' => ($index === 0), // First image is primary
                    'sort_order' => ($index + 1),
                ]);
            }
        }

        $property->load('images');

        return $property;
    }

    /**
     * Update property (without images)
     * @param int $id
     * @param array $data
     * @return object|null
     */
    public function updateProperty(int $id, array $data): ?object
    {
        $property = Property::find($id);

        if (! $property) {
            return null;
        }

        // Remove images from data if present
        unset($data['images']);

        // Update property data
        $property->update($data);

        // Load images relationship
        $property->load('images');

        return $property;
    }

    /**
     * Delete property and its images
     * @param int $id
     * @return bool
     */
    public function deleteProperty(int $id): bool
    {
        $property = Property::find($id);

        if (! $property) {
            return false;
        }

        // Delete associated images
        $property->images()->delete();

        return $property->delete();
    }
}
