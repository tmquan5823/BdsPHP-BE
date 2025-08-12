<?php

namespace Database\Seeders;

use App\Models\Property;
use App\Models\PropertyImage;
use Illuminate\Database\Seeder;

class PropertyImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Lấy tất cả properties
        $properties = Property::all();

        if ($properties->isEmpty()) {
            $this->command->error('Không tìm thấy property nào. Vui lòng chạy PropertySeeder trước.');

            return;
        }

        $sampleImages = [
            '/storage/properties/sample1.jpg',
            '/storage/properties/sample2.jpg',
            '/storage/properties/sample3.jpg',
            '/storage/properties/sample4.jpg',
            '/storage/properties/sample5.jpg',
        ];

        foreach ($properties as $property) {
            // Tạo 2-4 images cho mỗi property
            $imageCount = rand(2, 4);

            for ($i = 0; $i < $imageCount; $i++) {
                $imagePath = $sampleImages[array_rand($sampleImages)];

                PropertyImage::create([
                    'property_id' => $property->id,
                    'image_path' => $imagePath,
                    'image_name' => basename($imagePath),
                    'is_primary' => $i === 0, // Image đầu tiên là primary
                    'sort_order' => $i,
                ]);
            }
        }

        $this->command->info('Đã tạo images cho ' . $properties->count() . ' properties.');
    }
}
