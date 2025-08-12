<?php

namespace App\Http\Validations;

use App\Repositories\AuthRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthValidation
{
    protected $authRepository;

    public function __construct(AuthRepository $authRepository)
    {
        $this->authRepository = $authRepository;
    }

    /**
     * Validate login credentials
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function validateLogin(Request $request)
    {
        return Validator::make($request->all(), [
            'email' => [
                'required',
                'email',
                'max:255',
            ],
            'password' => [
                'required',
                'string',
                'max:255',
            ],
        ], [
            'email.required' => 'Email là bắt buộc',
            'email.email' => 'Email không hợp lệ',
            'email.max' => 'Email không được vượt quá 255 ký tự',
            'password.required' => 'Password là bắt buộc',
            'password.string' => 'Password phải là chuỗi',
            'password.max' => 'Password không được vượt quá 255 ký tự',
        ]);
    }

    /**
     * Validate signin data
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function validateSignin(Request $request)
    {
        return Validator::make($request->all(), [
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'email' => [
                'required',
                'email',
                'max:255',
                'unique:users,email',
            ],
            'password' => [
                'required',
                'string',
                'min:6',
                'max:255',
            ],
        ], [
            'name.required' => 'Tên là bắt buộc',
            'name.string' => 'Tên phải là chuỗi',
            'name.max' => 'Tên không được vượt quá 255 ký tự',
            'email.required' => 'Email là bắt buộc',
            'email.email' => 'Email không hợp lệ',
            'email.max' => 'Email không được vượt quá 255 ký tự',
            'email.unique' => 'Email này đã được sử dụng',
            'password.required' => 'Password là bắt buộc',
            'password.string' => 'Password phải là chuỗi',
            'password.min' => 'Password phải có ít nhất 6 ký tự',
            'password.max' => 'Password không được vượt quá 255 ký tự',
        ]);
    }

    /**
     * Validate user authentication
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function validateAuthentication(Request $request)
    {
        return Validator::make($request->all(), [
            'email' => [
                'required',
                'email',
                'max:255',
            ],
            'password' => [
                'required',
                'string',
                'max:255',
            ],
        ], [
            'email.required' => 'Email là bắt buộc',
            'email.email' => 'Email không hợp lệ',
            'email.max' => 'Email không được vượt quá 255 ký tự',
            'password.required' => 'Password là bắt buộc',
            'password.string' => 'Password phải là chuỗi',
            'password.max' => 'Password không được vượt quá 255 ký tự',
        ]);
    }
}
