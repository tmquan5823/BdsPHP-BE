<?php

namespace App\Repositories;

interface AuthRepositoryInterface
{
    /**
     * Get user by email
     * @param string $email
     * @return object|null
     */
    public function getUserByEmail($email): ?object;

    /**
     * Get user detail
     * @param int $id
     * @return object|null
     */
    public function getUserDetail($id): ?object;

    /**
     * Get user list
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function getUserList($request): array;

    /**
     * Get user for update
     * @param int $id
     * @return object|null
     */
    public function getUserForUpdate($id): ?object;

    /**
     * Create new user
     * @param array $data
     * @return object
     */
    public function createUser(array $data): object;

    /**
     * Update user
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function updateUser($id, array $data): bool;

    /**
     * Delete user
     * @param int $id
     * @return bool
     */
    public function deleteUser($id): bool;
}
