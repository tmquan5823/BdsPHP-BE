<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\BaseRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthRepository extends BaseRepository implements AuthRepositoryInterface
{
    /**
     * Get the model for the repository
     *
     * @return string
     */
    public function getModel()
    {
        return User::class;
    }

    /**
     * Get user by email
     * @param string $email
     * @return object|null
     */
    public function getUserByEmail($email): ?object
    {
        return $this->model->where('email', $email)->first();
    }

    /**
     * Get user detail
     * @param int $id
     * @return object|null
     */
    public function getUserDetail($id): ?object
    {
        return $this->model->select(
            'id',
            'name',
            'email',
            'created_at',
            'updated_at'
        )
            ->where('id', $id)
            ->first();
    }

    /**
     * Get user list
     * @param Request $request
     * @return array
     */
    public function getUserList($request): array
    {
        $query = $this->model->select(
            'id',
            'name',
            'email',
            'created_at',
            'updated_at'
        );

        // Filter by name
        if ($request->has('name') && $request->name !== null) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        // Filter by email
        if ($request->has('email') && $request->email !== null) {
            $query->where('email', 'like', '%' . $request->email . '%');
        }

        // Order by
        if ($request->has('order_by_name') && $request->order_by_name !== null) {
            $query->orderBy('name', $request->order_by_name);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        return $request->has('pagination')
            ? $query->paginate($request->pagination)->toArray()
            : $query->paginate(15)->toArray();
    }

    /**
     * Get user for update
     * @param int $id
     * @return object|null
     */
    public function getUserForUpdate($id): ?object
    {
        return $this->model->select(
            'id',
            'name',
            'email'
        )
            ->where('id', $id)
            ->first();
    }

    /**
     * Create new user (signin)
     * @param array $data
     * @return object
     */
    public function createUser(array $data): object
    {
        // Hash password if provided
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        return $this->model->create($data);
    }

    /**
     * Update user
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function updateUser($id, array $data): bool
    {
        // Hash password if provided
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        return $this->update($id, $data);
    }

    /**
     * Delete user
     * @param int $id
     * @return bool
     */
    public function deleteUser($id): bool
    {
        return $this->delete($id);
    }

    /**
     * Authenticate user for login
     * @param string $email
     * @param string $password
     * @return object|null
     */
    public function authenticateUser($email, $password): ?object
    {
        $user = $this->getUserByEmail($email);

        if ($user && Hash::check($password, $user->password)) {
            return $user;
        }

        return null;
    }

    /**
     * Create user token for login
     * @param object $user
     * @return string
     */
    public function createUserToken($user): string
    {
        return $user->createToken('auth-token')->plainTextToken;
    }

    /**
     * Revoke user token for logout
     * @param object $user
     * @return bool
     */
    public function revokeUserToken($user): bool
    {
        return $user->currentAccessToken()->delete();
    }

    /**
     * Get user data for login response
     * @param object $user
     * @return array
     */
    public function getUserLoginData($user): array
    {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
        ];
    }
}
