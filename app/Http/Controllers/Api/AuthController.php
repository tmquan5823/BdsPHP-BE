<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Validations\AuthValidation;
use App\Services\AuthService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    protected $authService;
    protected $authValidation;

    public function __construct(AuthService $authService, AuthValidation $authValidation)
    {
        $this->authService = $authService;
        $this->authValidation = $authValidation;
    }

    /**
     * Helper method để tạo response format nhất quán
     */
    private function response($status, $message, $code, $data = null)
    {
        return response()->json([
            'status' => $status,
            'message' => $message,
            'code' => $code,
            'data' => $data,
        ], $code);
    }

    /**
     * User login
     */
    public function login(Request $request)
    {
        $validator = $this->authValidation->validateLogin($request);

        if ($validator->fails()) {
            return $this->response(
                'error',
                'Dữ liệu không hợp lệ',
                422,
                ['errors' => $validator->errors()]
            );
        }

        try {
            $result = $this->authService->login($validator->validated());
            return $this->response('success', 'Đăng nhập thành công', 200, $result);
        } catch (\App\Exceptions\Auth\AuthenticationException $e) {
            return $this->response('error', $e->getMessage(), $e->getCode());
        } catch (\Exception $e) {
            return $this->response('error', 'Đăng nhập thất bại', 500, ['message' => $e->getMessage()]);
        }
    }

    /**
     * User signin (register)
     */
    public function signin(Request $request)
    {
        $validator = $this->authValidation->validateSignin($request);

        if ($validator->fails()) {
            return $this->response(
                'error',
                'Dữ liệu không hợp lệ',
                422,
                ['errors' => $validator->errors()]
            );
        }

        try {
            $result = $this->authService->signin($validator->validated());
            return $this->response('success', 'Đăng ký thành công', 201, $result);
        } catch (\Exception $e) {
            return $this->response('error', 'Đăng ký thất bại', 500, ['message' => $e->getMessage()]);
        }
    }

    /**
     * User logout
     */
    public function logout(Request $request)
    {
        try {
            $result = $this->authService->logout($request->user());
            return $this->response('success', 'Đăng xuất thành công', 200, $result);
        } catch (\Exception $e) {
            return $this->response('error', 'Đăng xuất thất bại', 500, ['message' => $e->getMessage()]);
        }
    }

    /**
     * Get authenticated user info
     */
    public function me(Request $request)
    {
        try {
            $userData = [
                'id' => $request->user()->id,
                'name' => $request->user()->name,
                'email' => $request->user()->email,
            ];
            return $this->response('success', 'Lấy thông tin thành công', 200, ['user' => $userData]);
        } catch (\Exception $e) {
            return $this->response('error', 'Không thể lấy thông tin người dùng', 500, ['message' => $e->getMessage()]);
        }
    }

    /**
     * Get user list
     */
    public function getUserList(Request $request)
    {
        try {
            $validated = $request->validate([
                'per_page' => 'sometimes|integer|min:1|max:100',
                'search' => 'sometimes|string|max:255',
            ]);

            $users = app(\App\Repositories\AuthRepository::class)->getUserList($request);
            return $this->response('success', 'Lấy danh sách thành công', 200, $users);
        } catch (\Exception $e) {
            return $this->response('error', 'Không thể lấy danh sách người dùng', 500, ['message' => $e->getMessage()]);
        }
    }

    /**
     * Get user detail
     */
    public function getUserDetail($id)
    {
        try {
            if (!is_numeric($id) || $id <= 0) {
                return $this->response('error', 'ID không hợp lệ', 422);
            }

            $user = app(\App\Repositories\AuthRepository::class)->getUserDetail($id);

            if (!$user) {
                return $this->response('error', 'Không tìm thấy người dùng', 404);
            }

            return $this->response('success', 'Lấy thông tin thành công', 200, ['user' => $user]);
        } catch (\Exception $e) {
            return $this->response('error', 'Không thể lấy thông tin người dùng', 500, ['message' => $e->getMessage()]);
        }
    }

    /**
     * Update user
     */
    public function updateUser(Request $request, $id)
    {
        try {
            if (!is_numeric($id) || $id <= 0) {
                return $this->response('error', 'ID không hợp lệ', 422);
            }

            $validated = $request->validate([
                'name' => 'sometimes|string|max:255',
                'email' => 'sometimes|email|unique:users,email,' . $id,
                'password' => 'sometimes|string|min:6',
            ]);

            $success = app(\App\Repositories\AuthRepository::class)->updateUser($id, $validated);

            if (!$success) {
                return $this->response('error', 'Cập nhật thất bại', 400);
            }

            return $this->response('success', 'Cập nhật thành công', 200);
        } catch (\Exception $e) {
            return $this->response('error', 'Cập nhật thất bại', 500, ['message' => $e->getMessage()]);
        }
    }

    /**
     * Delete user
     */
    public function deleteUser($id)
    {
        try {
            if (!is_numeric($id) || $id <= 0) {
                return $this->response('error', 'ID không hợp lệ', 422);
            }

            $success = app(\App\Repositories\AuthRepository::class)->deleteUser($id);

            if (!$success) {
                return $this->response('error', 'Xóa thất bại', 400);
            }

            return $this->response('success', 'Xóa thành công', 200);
        } catch (\Exception $e) {
            return $this->response('error', 'Xóa thất bại', 500, ['message' => $e->getMessage()]);
        }
    }
}
