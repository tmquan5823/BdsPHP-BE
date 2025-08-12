<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropertyImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_id',
        'image_path',
        'image_name',
        'is_primary',
        'sort_order',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Relationship with Property
     */
    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    /**
     * Get full image URL
     */
    public function getImageUrlAttribute()
    {
        if (str_starts_with($this->image_path, 'http')) {
            return $this->image_path;
        }

        return asset('storage/' . $this->image_path);
    }

    /**
     * Get thumbnail URL
     */
    public function getThumbnailUrlAttribute()
    {
        $path = $this->image_path;
        if (str_starts_with($path, 'http')) {
            return $path;
        }

        // Convert to thumbnail path
        $pathInfo = pathinfo($path);
        $thumbnailPath = $pathInfo['dirname'] . '/thumbnails/' . $pathInfo['basename'];

        return asset('storage/' . $thumbnailPath);
    }

    /**
     * Scope for primary images
     */
    public function scopePrimary($query)
    {
        return $query->where('is_primary', true);
    }

    /**
     * Scope for non-primary images
     */
    public function scopeNonPrimary($query)
    {
        return $query->where('is_primary', false);
    }

    /**
     * Scope ordered by sort order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order', 'asc');
    }

    /**
     * Set as primary image
     */
    public function setAsPrimary()
    {
        // Remove primary from other images of the same property
        $this->property->images()
            ->where('id', '!=', $this->id)
            ->update(['is_primary' => false]);

        // Set this image as primary
        $this->update(['is_primary' => true]);
    }

    /**
     * Remove primary status
     */
    public function removePrimary()
    {
        $this->update(['is_primary' => false]);
    }

    /**
     * Move image up in order
     */
    public function moveUp()
    {
        $previousImage = $this->property->images()
            ->where('sort_order', '<', $this->sort_order)
            ->orderBy('sort_order', 'desc')
            ->first();

        if ($previousImage) {
            $tempOrder = $this->sort_order;
            $this->update(['sort_order' => $previousImage->sort_order]);
            $previousImage->update(['sort_order' => $tempOrder]);
        }
    }

    /**
     * Move image down in order
     */
    public function moveDown()
    {
        $nextImage = $this->property->images()
            ->where('sort_order', '>', $this->sort_order)
            ->orderBy('sort_order', 'asc')
            ->first();

        if ($nextImage) {
            $tempOrder = $this->sort_order;
            $this->update(['sort_order' => $nextImage->sort_order]);
            $nextImage->update(['sort_order' => $tempOrder]);
        }
    }
}
