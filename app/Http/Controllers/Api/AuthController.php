<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\AuthRepository;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    protected $authRepository;

    public function __construct(AuthRepository $authRepository)
    {
        $this->authRepository = $authRepository;
    }

    /**
     * User login
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = $this->authRepository->authenticateUser($request->email, $request->password);

        if (!$user) {
            throw ValidationException::withMessages([
                'email' => ['Thông tin đăng nhập không chính xác.'],
            ]);
        }

        // Tạo token
        $token = $this->authRepository->createUserToken($user);

        return response()->json([
            'token' => $token,
            'user' => $this->authRepository->getUserLoginData($user),
        ]);
    }

    /**
     * User signin (register)
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function signin(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
        ]);

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
        ];

        $user = $this->authRepository->createUser($userData);

        // Tạo token
        $token = $this->authRepository->createUserToken($user);

        return response()->json([
            'token' => $token,
            'user' => $this->authRepository->getUserLoginData($user),
            'message' => 'Đăng ký thành công'
        ], 201);
    }

    /**
     * User logout
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        $this->authRepository->revokeUserToken($request->user());

        return response()->json([
            'message' => 'Đăng xuất thành công'
        ]);
    }

    /**
     * Get authenticated user info
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function me(Request $request)
    {
        return response()->json([
            'user' => $this->authRepository->getUserLoginData($request->user()),
        ]);
    }

    /**
     * Get user list (admin function)
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserList(Request $request)
    {
        $users = $this->authRepository->getUserList($request);

        return response()->json($users);
    }

    /**
     * Get user detail
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserDetail($id)
    {
        $user = $this->authRepository->getUserDetail($id);

        if (!$user) {
            return response()->json([
                'message' => 'Không tìm thấy người dùng'
            ], 404);
        }

        return response()->json([
            'user' => $user
        ]);
    }

    /**
     * Update user
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateUser(Request $request, $id)
    {
        $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $id,
            'password' => 'sometimes|string|min:6',
        ]);

        $userData = $request->only(['name', 'email', 'password']);

        $success = $this->authRepository->updateUser($id, $userData);

        if (!$success) {
            return response()->json([
                'message' => 'Cập nhật thất bại'
            ], 400);
        }

        return response()->json([
            'message' => 'Cập nhật thành công'
        ]);
    }

    /**
     * Delete user
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteUser($id)
    {
        $success = $this->authRepository->deleteUser($id);

        if (!$success) {
            return response()->json([
                'message' => 'Xóa thất bại'
            ], 400);
        }

        return response()->json([
            'message' => 'Xóa thành công'
        ]);
    }
}
