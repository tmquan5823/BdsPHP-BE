<?php

namespace App\Services;

use App\Exceptions\Auth\AuthenticationException;
use App\Repositories\AuthRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    protected $authRepository;

    public function __construct(AuthRepository $authRepository)
    {
        $this->authRepository = $authRepository;
    }

    /**
     * User login
     *
     * @param array $credentials
     * @return array
     * @throws AuthenticationException
     */
    public function login(array $credentials): array
    {
        try {
            // Validate authentication
            $user = $this->authRepository->authenticateUser($credentials['email'], $credentials['password']);

            if (!$user) {
                throw new AuthenticationException('Thông tin đăng nhập không chính xác.');
            }

            // Xóa tất cả token cũ của user trước khi tạo token mới
            $user->tokens()->delete();

            // Create token
            $token = $this->authRepository->createUserToken($user);

            return [
                'token' => $token,
                'user' => $this->authRepository->getUserLoginData($user),
            ];
        } catch (AuthenticationException $e) {
            throw $e;
        } catch (\Exception $e) {
            throw new AuthenticationException('Đăng nhập thất bại: ' . $e->getMessage());
        }
    }

    /**
     * User signin (register)
     *
     * @param array $userData
     * @return array
     */
    public function signin(array $userData): array
    {
        try {
            // Create user
            $user = $this->authRepository->createUser($userData);

            // Create token
            $token = $this->authRepository->createUserToken($user);

            return [
                'token' => $token,
                'user' => $this->authRepository->getUserLoginData($user),
                'message' => 'Đăng ký thành công'
            ];
        } catch (\Exception $e) {
            throw new \Exception('Đăng ký thất bại: ' . $e->getMessage());
        }
    }

    /**
     * User logout
     *
     * @param object $user
     * @return array
     */
    public function logout($user): array
    {
        try {
            // Revoke user token
            $this->authRepository->revokeUserToken($user);

            return [
                'message' => 'Đăng xuất thành công'
            ];
        } catch (\Exception $e) {
            // Log error but don't expose it to user
            \Log::error('Logout error: ' . $e->getMessage());

            return [
                'message' => 'Đăng xuất thành công'
            ];
        }
    }
}
