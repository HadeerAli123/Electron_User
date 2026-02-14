<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Eloquent\BaseRepository;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    /**
     * UserRepository constructor.
     */
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    /**
     * Find user by email
     */
    public function findByEmail(string $email)
    {
        return $this->findBy('email', $email);
    }

    /**
     * Find user by national ID
     */
    public function findByNationalId(string $nationalId)
    {
        return $this->findBy('national_id', $nationalId);
    }

    /**
     * Get all customers
     */
    public function getCustomers()
    {
        return $this->model->where('role', 'customer')->get();
    }

    /**
     * Get all agents
     */
    public function getAgents()
    {
        return $this->model->where('role', 'agent')->get();
    }

    /**
     * Create user with hashed password
     */
    public function createUser(array $data)
    {
        $data['password'] = Hash::make($data['password']);
        return $this->create($data);
    }

    /**
     * Update user password
     */
    public function updatePassword(int $userId, string $newPassword): bool
    {
        $user = $this->find($userId);
        $user->password = Hash::make($newPassword);
        return $user->save();
    }

    /**
     * Get user with orders
     */
    public function getUserWithOrders(int $userId)
    {
        return $this->model->with('orders.orderItems.product')->findOrFail($userId);
    }

    /**
     * Get user with cart
     */
    public function getUserWithCart(int $userId)
    {
        return $this->model->with('cart.cartItems.product')->findOrFail($userId);
    }

    /**
     * Get user referral codes
     */
    public function getUserReferralCodes(int $userId)
    {
        return $this->model->with('referralCodes')->findOrFail($userId)->referralCodes;
    }
}