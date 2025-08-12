<?php

namespace Database\Seeders;

use App\Models\Property;
use App\Models\User;
use Illuminate\Database\Seeder;

class PropertySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Lấy user đầu tiên để làm created_by
        $user = User::first();

        if (! $user) {
            $this->command->error('Không tìm thấy user nào. Vui lòng chạy UserSeeder trước.');

            return;
        }

        $properties = [
            [
                'title' => 'Căn hộ cao cấp Quận 1',
                'description' => 'Căn hộ cao cấp tại trung tâm Quận 1, view đẹp, tiện ích đầy đủ. Gần các trung tâm thương mại, trường học và bệnh viện.',
                'property_type' => 'apartment',
                'status' => 'available',
                'price' => 1500000000,
                'area' => 85.5,
                'bedrooms' => 2,
                'bathrooms' => 2,
                'floors' => 15,
                'address' => '123 Nguyễn Huệ, Quận 1',
                'city' => 'Hồ Chí Minh',
                'district' => 'Quận 1',
                'postal_code' => '70000',
                'latitude' => 10.7769,
                'longitude' => 106.7009,
                'year_built' => 2020,
                'features' => ['Gym', 'Hồ bơi', 'Bảo vệ 24/7', 'Thang máy', 'Chỗ để xe'],
                'images' => [
                    ['id' => 1, 'image_path' => '/storage/properties/1.jpg', 'is_primary' => true],
                    ['id' => 2, 'image_path' => '/storage/properties/1_2.jpg', 'is_primary' => false],
                    ['id' => 3, 'image_path' => '/storage/properties/1_3.jpg', 'is_primary' => false],
                ],
                'contact_name' => 'Nguyễn Văn A',
                'contact_phone' => '0901234567',
                'contact_email' => 'contact@example.com',
                'created_by' => $user->id,
            ],
            [
                'title' => 'Nhà riêng 3 tầng Quận 2',
                'description' => 'Nhà riêng 3 tầng tại Quận 2, thiết kế hiện đại, không gian rộng rãi. Phù hợp cho gia đình lớn.',
                'property_type' => 'house',
                'status' => 'available',
                'price' => 8500000000,
                'area' => 200.0,
                'bedrooms' => 4,
                'bathrooms' => 3,
                'floors' => 3,
                'address' => '456 Mai Chí Thọ, Quận 2',
                'city' => 'Hồ Chí Minh',
                'district' => 'Quận 2',
                'postal_code' => '70000',
                'latitude' => 10.7870,
                'longitude' => 106.7498,
                'year_built' => 2018,
                'features' => ['Sân vườn', 'Nhà để xe', 'Hệ thống an ninh', 'Bếp hiện đại'],
                'images' => [
                    ['id' => 4, 'image_path' => '/storage/properties/2.jpg', 'is_primary' => true],
                    ['id' => 5, 'image_path' => '/storage/properties/2_2.jpg', 'is_primary' => false],
                ],
                'contact_name' => 'Trần Thị B',
                'contact_phone' => '0912345678',
                'contact_email' => 'tranthi@example.com',
                'created_by' => $user->id,
            ],
            [
                'title' => 'Biệt thự Vinhomes Central Park',
                'description' => 'Biệt thự cao cấp tại Vinhomes Central Park, thiết kế sang trọng, tiện ích 5 sao.',
                'property_type' => 'villa',
                'status' => 'sold',
                'price' => 25000000000,
                'area' => 350.0,
                'bedrooms' => 5,
                'bathrooms' => 4,
                'floors' => 2,
                'address' => 'Vinhomes Central Park, Quận Bình Thạnh',
                'city' => 'Hồ Chí Minh',
                'district' => 'Quận Bình Thạnh',
                'postal_code' => '70000',
                'latitude' => 10.8000,
                'longitude' => 106.7200,
                'year_built' => 2019,
                'features' => ['Hồ bơi riêng', 'Sân tennis', 'Vườn thượng', 'Hầm rượu', 'Thang máy'],
                'images' => [
                    ['id' => 6, 'image_path' => '/storage/properties/3.jpg', 'is_primary' => true],
                    ['id' => 7, 'image_path' => '/storage/properties/3_2.jpg', 'is_primary' => false],
                    ['id' => 8, 'image_path' => '/storage/properties/3_3.jpg', 'is_primary' => false],
                    ['id' => 9, 'image_path' => '/storage/properties/3_4.jpg', 'is_primary' => false],
                ],
                'contact_name' => 'Lê Văn C',
                'contact_phone' => '0923456789',
                'contact_email' => 'levan@example.com',
                'created_by' => $user->id,
            ],
            [
                'title' => 'Văn phòng cho thuê Quận 3',
                'description' => 'Văn phòng cho thuê tại Quận 3, vị trí đắc địa, giao thông thuận tiện.',
                'property_type' => 'office',
                'status' => 'rented',
                'price' => 25000000,
                'area' => 120.0,
                'bedrooms' => 0,
                'bathrooms' => 2,
                'floors' => 8,
                'address' => '789 Võ Văn Tần, Quận 3',
                'city' => 'Hồ Chí Minh',
                'district' => 'Quận 3',
                'postal_code' => '70000',
                'latitude' => 10.7800,
                'longitude' => 106.6900,
                'year_built' => 2017,
                'features' => ['Thang máy', 'Bảo vệ 24/7', 'Chỗ để xe', 'Hệ thống điều hòa'],
                'images' => [
                    ['id' => 10, 'image_path' => '/storage/properties/4.jpg', 'is_primary' => true],
                ],
                'contact_name' => 'Phạm Thị D',
                'contact_phone' => '0934567890',
                'contact_email' => 'phamthi@example.com',
                'created_by' => $user->id,
            ],
            [
                'title' => 'Đất nền dự án Quận 7',
                'description' => 'Đất nền dự án tại Quận 7, tiềm năng đầu tư cao, hạ tầng hoàn thiện.',
                'property_type' => 'land',
                'status' => 'available',
                'price' => 3500000000,
                'area' => 150.0,
                'bedrooms' => 0,
                'bathrooms' => 0,
                'floors' => 0,
                'address' => 'Dự án Phú Mỹ Hưng, Quận 7',
                'city' => 'Hồ Chí Minh',
                'district' => 'Quận 7',
                'postal_code' => '70000',
                'latitude' => 10.7300,
                'longitude' => 106.7200,
                'year_built' => null,
                'features' => ['Hạ tầng hoàn thiện', 'Giao thông thuận tiện', 'Tiện ích xung quanh'],
                'images' => [
                    ['id' => 11, 'image_path' => '/storage/properties/5.jpg', 'is_primary' => true],
                    ['id' => 12, 'image_path' => '/storage/properties/5_2.jpg', 'is_primary' => false],
                ],
                'contact_name' => 'Hoàng Văn E',
                'contact_phone' => '0945678901',
                'contact_email' => 'hoangvan@example.com',
                'created_by' => $user->id,
            ],
            [
                'title' => 'Căn hộ chung cư Quận 9',
                'description' => 'Căn hộ chung cư tại Quận 9, giá tốt, phù hợp cho gia đình trẻ.',
                'property_type' => 'apartment',
                'status' => 'pending',
                'price' => 850000000,
                'area' => 65.0,
                'bedrooms' => 2,
                'bathrooms' => 1,
                'floors' => 12,
                'address' => 'Chung cư The Manor, Quận 9',
                'city' => 'Hồ Chí Minh',
                'district' => 'Quận 9',
                'postal_code' => '70000',
                'latitude' => 10.8500,
                'longitude' => 106.7800,
                'year_built' => 2021,
                'features' => ['Gym', 'Hồ bơi', 'Sân chơi trẻ em', 'Chỗ để xe'],
                'images' => [
                    ['id' => 13, 'image_path' => '/storage/properties/6.jpg', 'is_primary' => true],
                ],
                'contact_name' => 'Vũ Thị F',
                'contact_phone' => '0956789012',
                'contact_email' => 'vuthi@example.com',
                'created_by' => $user->id,
            ],
        ];

        foreach ($properties as $propertyData) {
            // Tách images ra khỏi property data
            $images = $propertyData['images'];
            unset($propertyData['images']);

            // Tạo property
            $property = Property::create($propertyData);

            // Tạo property images
            foreach ($images as $imageData) {
                $property->images()->create([
                    'image_path' => $imageData['image_path'],
                    'image_name' => basename($imageData['image_path']),
                    'is_primary' => $imageData['is_primary'],
                    'sort_order' => $imageData['id'] % 10, // Sử dụng ID để tạo sort_order
                ]);
            }
        }

        $this->command->info('Đã tạo ' . count($properties) . ' properties với images.');
    }
}
