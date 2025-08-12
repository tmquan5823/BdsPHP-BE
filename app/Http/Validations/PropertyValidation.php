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
            'price.required' => 'Giá tiền là bắt buộc',
            'price.numeric' => 'Giá tiền phải là số',
            'price.min' => 'Giá tiền không được âm',
            'area.required' => 'Diện tích là bắt buộc',
            'area.numeric' => 'Diện tích phải là số',
            'area.min' => 'Diện tích không được âm',
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
            'contact_name.required' => 'Tên liên hệ là bắt buộc',
            'contact_name.string' => 'Tên liên hệ phải là chuỗi',
            'contact_name.max' => 'Tên liên hệ không được vượt quá 100 ký tự',
            'contact_phone.required' => 'Số điện thoại là bắt buộc',
            'contact_phone.string' => 'Số điện thoại phải là chuỗi',
            'contact_phone.max' => 'Số điện thoại không được vượt quá 20 ký tự',
            'images.*.file' => 'File phải là file hợp lệ',
            'images.*.image' => 'File phải là hình ảnh',
            'images.*.mimes' => 'Hình ảnh phải có định dạng: jpeg, png, jpg, gif, webp',
            'images.*.max' => 'Kích thước hình ảnh không được vượt quá 5MB',
        ]);
    }

    /**
     * Validate property update data with file uploads
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
            'images.*' => [
                'nullable',
                'file',
                'image',
                'mimes:jpeg,png,jpg,gif,webp',
                'max:5120', // 5MB
            ],
        ], [
            'title.string' => 'Tiêu đề phải là chuỗi',
            'title.max' => 'Tiêu đề không được vượt quá 255 ký tự',
            'price.numeric' => 'Giá tiền phải là số',
            'price.min' => 'Giá tiền không được âm',
            'area.numeric' => 'Diện tích phải là số',
            'area.min' => 'Diện tích không được âm',
            'city.string' => 'Thành phố phải là chuỗi',
            'city.max' => 'Thành phố không được vượt quá 100 ký tự',
            'district.string' => 'Quận/Huyện phải là chuỗi',
            'district.max' => 'Quận/Huyện không được vượt quá 100 ký tự',
            'status.string' => 'Trạng thái phải là chuỗi',
            'status.in' => 'Trạng thái phải là một trong: available, sold, rented, pending',
            'address.string' => 'Địa chỉ phải là chuỗi',
            'address.max' => 'Địa chỉ không được vượt quá 500 ký tự',
            'contact_name.string' => 'Tên liên hệ phải là chuỗi',
            'contact_name.max' => 'Tên liên hệ không được vượt quá 100 ký tự',
            'contact_phone.string' => 'Số điện thoại phải là chuỗi',
            'contact_phone.max' => 'Số điện thoại không được vượt quá 20 ký tự',
            'images.*.file' => 'File phải là file hợp lệ',
            'images.*.image' => 'File phải là hình ảnh',
            'images.*.mimes' => 'Hình ảnh phải có định dạng: jpeg, png, jpg, gif, webp',
            'images.*.max' => 'Kích thước hình ảnh không được vượt quá 5MB',
        ]);
    }
}
