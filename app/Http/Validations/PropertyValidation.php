<?php

namespace App\Http\Validations;

use App\Repositories\PropertyRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PropertyValidation
{
    protected $propertyRepository;

    public function __construct(PropertyRepository $propertyRepository)
    {
        $this->propertyRepository = $propertyRepository;
    }

    /**
     * Validate property creation data with file uploads
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function validateCreateProperty(Request $request)
    {
        return Validator::make($request->all(), [
            'title' => [
                'required',
                'string',
                'max:255',
            ],
            'description' => [
                'nullable',
                'string',
            ],
            'property_type' => [
                'nullable',
                'string',
            ],
            'price' => [
                'required',
                'numeric',
                'min:0',
            ],
            'area' => [
                'required',
                'numeric',
                'min:0',
            ],
            'bedrooms' => [
                'nullable',
                'integer',
                'min:0',
            ],
            'bathrooms' => [
                'nullable',
                'integer',
                'min:0',
            ],
            'floors' => [
                'nullable',
                'integer',
                'min:1',
            ],
            'city' => [
                'required',
                'string',
                'max:100',
            ],
            'district' => [
                'required',
                'string',
                'max:100',
            ],
            'status' => [
                'required',
                'string',
                'in:available,sold,rented,pending',
            ],
            'address' => [
                'required',
                'string',
                'max:500',
            ],
            'postal_code' => [
                'nullable',
                'string',
                'max:20',
            ],
            'latitude' => [
                'nullable',
                'numeric',
            ],
            'longitude' => [
                'nullable',
                'numeric',
            ],
            'year_built' => [
                'nullable',
                'integer',
                'min:1800',
                'max:' . (date('Y') + 1),
            ],
            'features' => [
                'nullable',
                'array',
            ],
            'contact_name' => [
                'required',
                'string',
                'max:100',
            ],
            'contact_phone' => [
                'required',
                'string',
                'max:20',
            ],
            'contact_email' => [
                'nullable',
                'email',
                'max:255',
            ],
            'images.*' => [
                'nullable',
                'file',
                'image',
                'mimes:jpeg,png,jpg,gif,webp',
                'max:5120',
            ],
        ], [
            'title.required' => 'Tiêu đề là bắt buộc',
            'title.string' => 'Tiêu đề phải là chuỗi',
            'title.max' => 'Tiêu đề không được vượt quá 255 ký tự',
            'description.string' => 'Mô tả phải là chuỗi',
            'property_type.string' => 'Loại bất động sản phải là chuỗi',
            'price.required' => 'Giá tiền là bắt buộc',
            'price.numeric' => 'Giá tiền phải là số',
            'price.min' => 'Giá tiền không được âm',
            'area.required' => 'Diện tích là bắt buộc',
            'area.numeric' => 'Diện tích phải là số',
            'area.min' => 'Diện tích không được âm',
            'bedrooms.integer' => 'Số phòng ngủ phải là số nguyên',
            'bedrooms.min' => 'Số phòng ngủ không được âm',
            'bathrooms.integer' => 'Số phòng tắm phải là số nguyên',
            'bathrooms.min' => 'Số phòng tắm không được âm',
            'floors.integer' => 'Số tầng phải là số nguyên',
            'floors.min' => 'Số tầng phải lớn hơn 0',
            'city.required' => 'Thành phố là bắt buộc',
            'city.string' => 'Thành phố phải là chuỗi',
            'city.max' => 'Thành phố không được vượt quá 100 ký tự',
            'district.required' => 'Quận/Huyện là bắt buộc',
            'district.string' => 'Quận/Huyện phải là chuỗi',
            'district.max' => 'Quận/Huyện không được vượt quá 100 ký tự',
            'status.required' => 'Trạng thái là bắt buộc',
            'status.string' => 'Trạng thái phải là chuỗi',
            'status.in' => 'Trạng thái phải là một trong: available, sold, rented, pending',
            'address.required' => 'Địa chỉ là bắt buộc',
            'address.string' => 'Địa chỉ phải là chuỗi',
            'address.max' => 'Địa chỉ không được vượt quá 500 ký tự',
            'postal_code.string' => 'Mã bưu điện phải là chuỗi',
            'postal_code.max' => 'Mã bưu điện không được vượt quá 20 ký tự',
            'latitude.numeric' => 'Vĩ độ phải là số',
            'longitude.numeric' => 'Kinh độ phải là số',
            'year_built.integer' => 'Năm xây dựng phải là số nguyên',
            'year_built.min' => 'Năm xây dựng phải từ 1800 trở lên',
            'year_built.max' => 'Năm xây dựng không được vượt quá năm hiện tại',
            'features.array' => 'Tính năng phải là mảng',
            'contact_name.required' => 'Tên liên hệ là bắt buộc',
            'contact_name.string' => 'Tên liên hệ phải là chuỗi',
            'contact_name.max' => 'Tên liên hệ không được vượt quá 100 ký tự',
            'contact_phone.required' => 'Số điện thoại là bắt buộc',
            'contact_phone.string' => 'Số điện thoại phải là chuỗi',
            'contact_phone.max' => 'Số điện thoại không được vượt quá 20 ký tự',
            'contact_email.email' => 'Email không đúng định dạng',
            'contact_email.max' => 'Email không được vượt quá 255 ký tự',
            'images.*.file' => 'File phải là file hợp lệ',
            'images.*.image' => 'File phải là hình ảnh',
            'images.*.mimes' => 'Hình ảnh phải có định dạng: jpeg, png, jpg, gif, webp',
            'images.*.max' => 'Kích thước hình ảnh không được vượt quá 5MB',
        ]);
    }

    /**
     * Validate property update data (without images)
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function validateUpdateProperty(Request $request)
    {
        return Validator::make($request->all(), [
            'title' => [
                'sometimes',
                'string',
                'max:255',
            ],
            'description' => [
                'sometimes',
                'string',
            ],
            'property_type' => [
                'sometimes',
                'string',
            ],
            'price' => [
                'sometimes',
                'numeric',
                'min:0',
            ],
            'area' => [
                'sometimes',
                'numeric',
                'min:0',
            ],
            'bedrooms' => [
                'sometimes',
                'integer',
                'min:0',
            ],
            'bathrooms' => [
                'sometimes',
                'integer',
                'min:0',
            ],
            'floors' => [
                'sometimes',
                'integer',
                'min:1',
            ],
            'city' => [
                'sometimes',
                'string',
                'max:100',
            ],
            'district' => [
                'sometimes',
                'string',
                'max:100',
            ],
            'status' => [
                'sometimes',
                'string',
                'in:available,sold,rented,pending',
            ],
            'address' => [
                'sometimes',
                'string',
                'max:500',
            ],
            'postal_code' => [
                'sometimes',
                'string',
                'max:20',
            ],
            'latitude' => [
                'sometimes',
                'numeric',
            ],
            'longitude' => [
                'sometimes',
                'numeric',  
            ],
            'year_built' => [
                'sometimes',
                'integer',
                'min:1800',
                'max:' . (date('Y') + 1),
            ],
            'features' => [
                'sometimes',
                'array',
            ],
            'contact_name' => [
                'sometimes',
                'string',
                'max:100',
            ],
            'contact_phone' => [
                'sometimes',
                'string',
                'max:20',
            ],
            'contact_email' => [
                'sometimes',
                'email',
                'max:255',
            ],
        ], [
            'title.string' => 'Tiêu đề phải là chuỗi',
            'title.max' => 'Tiêu đề không được vượt quá 255 ký tự',
            'description.string' => 'Mô tả phải là chuỗi',
            'property_type.string' => 'Loại bất động sản phải là chuỗi',
            'price.numeric' => 'Giá tiền phải là số',
            'price.min' => 'Giá tiền không được âm',
            'area.numeric' => 'Diện tích phải là số',
            'area.min' => 'Diện tích không được âm',
            'bedrooms.integer' => 'Số phòng ngủ phải là số nguyên',
            'bedrooms.min' => 'Số phòng ngủ không được âm',
            'bathrooms.integer' => 'Số phòng tắm phải là số nguyên',
            'bathrooms.min' => 'Số phòng tắm không được âm',
            'floors.integer' => 'Số tầng phải là số nguyên',
            'floors.min' => 'Số tầng phải lớn hơn 0',
            'city.string' => 'Thành phố phải là chuỗi',
            'city.max' => 'Thành phố không được vượt quá 100 ký tự',
            'district.string' => 'Quận/Huyện phải là chuỗi',
            'district.max' => 'Quận/Huyện không được vượt quá 100 ký tự',
            'status.string' => 'Trạng thái phải là chuỗi',
            'status.in' => 'Trạng thái phải là một trong: available, sold, rented, pending',
            'address.string' => 'Địa chỉ phải là chuỗi',
            'address.max' => 'Địa chỉ không được vượt quá 500 ký tự',
            'postal_code.string' => 'Mã bưu điện phải là chuỗi',
            'postal_code.max' => 'Mã bưu điện không được vượt quá 20 ký tự',
            'latitude.numeric' => 'Vĩ độ phải là số',
            'longitude.numeric' => 'Kinh độ phải là số',
            'year_built.integer' => 'Năm xây dựng phải là số nguyên',
            'year_built.min' => 'Năm xây dựng phải từ 1800 trở lên',
            'year_built.max' => 'Năm xây dựng không được vượt quá năm hiện tại',
            'features.array' => 'Tính năng phải là mảng',
            'contact_name.string' => 'Tên liên hệ phải là chuỗi',
            'contact_name.max' => 'Tên liên hệ không được vượt quá 100 ký tự',
            'contact_phone.string' => 'Số điện thoại phải là chuỗi',
            'contact_phone.max' => 'Số điện thoại không được vượt quá 20 ký tự',
            'contact_email.email' => 'Email không đúng định dạng',
            'contact_email.max' => 'Email không được vượt quá 255 ký tự',
        ]);
    }

    /**
     * Validate property image upload
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function validateUploadImages(Request $request)
    {
        return Validator::make($request->all(), [
            'images' => [
                'required',
                'array',
                'min:1',
            ],
            'images.*' => [
                'required',
                'file',
                'image',
                'mimes:jpeg,png,jpg,gif,webp',
                'max:5120', // 5MB
            ],
        ], [
            'images.required' => 'Ảnh là bắt buộc',
            'images.array' => 'Ảnh phải là mảng',
            'images.min' => 'Phải có ít nhất 1 ảnh',
            'images.*.required' => 'Mỗi ảnh là bắt buộc',
            'images.*.file' => 'File phải là file hợp lệ',
            'images.*.image' => 'File phải là hình ảnh',
            'images.*.mimes' => 'Hình ảnh phải có định dạng: jpeg, png, jpg, gif, webp',
            'images.*.max' => 'Kích thước hình ảnh không được vượt quá 5MB',
        ]);
    }
}
