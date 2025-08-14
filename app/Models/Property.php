<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Property extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
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
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'area' => 'decimal:2',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'features' => 'array',
        'bedrooms' => 'integer',
        'bathrooms' => 'integer',
        'floors' => 'integer',
        'year_built' => 'integer',
        'status' => 'string',
        'property_type' => 'string',
    ];

    protected $hidden = [
        'deleted_at',
    ];

    // Property types
    public const TYPE_APARTMENT = 'apartment';
    public const TYPE_HOUSE = 'house';
    public const TYPE_VILLA = 'villa';
    public const TYPE_OFFICE = 'office';
    public const TYPE_LAND = 'land';

    // Status types
    public const STATUS_AVAILABLE = 'available';
    public const STATUS_SOLD = 'sold';
    public const STATUS_RENTED = 'rented';
    public const STATUS_PENDING = 'pending';

    /**
     * Get property type options
     */
    public static function getPropertyTypes()
    {
        return [
            self::TYPE_APARTMENT => 'Căn hộ',
            self::TYPE_HOUSE => 'Nhà riêng',
            self::TYPE_VILLA => 'Biệt thự',
            self::TYPE_OFFICE => 'Văn phòng',
            self::TYPE_LAND => 'Đất nền',
        ];
    }

    /**
     * Get status options
     */
    public static function getStatusOptions()
    {
        return [
            self::STATUS_AVAILABLE => 'Có sẵn',
            self::STATUS_SOLD => 'Đã bán',
            self::STATUS_RENTED => 'Đã cho thuê',
            self::STATUS_PENDING => 'Chờ xử lý',
        ];
    }

    /**
     * Get property type label
     */
    public function getPropertyTypeLabelAttribute()
    {
        return self::getPropertyTypes()[$this->property_type] ?? $this->property_type;
    }

    /**
     * Get status label
     */
    public function getStatusLabelAttribute()
    {
        return self::getStatusOptions()[$this->status] ?? $this->status;
    }

    /**
     * Format price with currency
     */
    public function getFormattedPriceAttribute()
    {
        return number_format($this->price, 0, ',', '.') . ' VNĐ';
    }

    /**
     * Format area with unit
     */
    public function getFormattedAreaAttribute()
    {
        return number_format($this->area, 0, ',', '.') . ' m²';
    }

    /**
     * Relationship with PropertyImage
     */
    public function images()
    {
        return $this->hasMany(PropertyImage::class);
    }

    /**
     * Get primary image
     */
    public function primaryImage()
    {
        return $this->hasOne(PropertyImage::class)->where('is_primary', true);
    }

    /**
     * Relationship with User (creator)
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Relationship with User (updater)
     */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Scope for available properties
     */
    public function scopeAvailable($query)
    {
        return $query->where('status', self::STATUS_AVAILABLE);
    }

    /**
     * Scope for sold properties
     */
    public function scopeSold($query)
    {
        return $query->where('status', self::STATUS_SOLD);
    }

    /**
     * Scope for rented properties
     */
    public function scopeRented($query)
    {
        return $query->where('status', self::STATUS_RENTED);
    }

    /**
     * Scope by property type
     */
    public function scopeByType($query, $type)
    {
        return $query->where('property_type', $type);
    }

    /**
     * Scope by city
     */
    public function scopeByCity($query, $city)
    {
        return $query->where('city', $city);
    }

    /**
     * Scope by price range
     */
    public function scopeByPriceRange($query, $minPrice, $maxPrice)
    {
        return $query->whereBetween('price', [$minPrice, $maxPrice]);
    }

    /**
     * Scope by area range
     */
    public function scopeByAreaRange($query, $minArea, $maxArea)
    {
        return $query->whereBetween('area', [$minArea, $maxArea]);
    }
}
