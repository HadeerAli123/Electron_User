<?php

namespace App\Repositories;
use App\Models\User;
use App\Interfaces\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;


class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    public function findByEmail(string $email)
    {
        return $this->findBy('email', $email);
    }

    public function findByNationalId(string $nationalId)
    {
        return $this->findBy('national_id', $nationalId);
    }

    public function getCustomers()
    {
        return $this->model->where('role', 'customer')->get();
    }

    public function getAgents()
    {
        return $this->model->where('role', 'agent')->get();
    }

    public function createUser(array $data)
    {
        $data['password'] = Hash::make($data['password']);
        return $this->create($data);
    }

    public function updatePassword(int $userId, string $newPassword): bool
    {
        $user = $this->find($userId);
        $user->password = Hash::make($newPassword);
        return $user->save();
    }

    public function getUserWithOrders(int $userId)
    {
        return $this->model->with('orders.orderItems.product')->findOrFail($userId);
    }

    public function getUserWithCart(int $userId)
    {
        return $this->model->with('cart.cartItems.product')->findOrFail($userId);
    }

    public function getUserReferralCodes(int $userId)
    {
        return $this->model->with('referralCodes')->findOrFail($userId)->referralCodes;
    }
}