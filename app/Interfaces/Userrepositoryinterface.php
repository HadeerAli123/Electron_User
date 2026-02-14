<?php

namespace App\Repositories\Interfaces;

interface UserRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Find user by email
     */
    public function findByEmail(string $email);

    /**
     * Find user by national ID
     */
    public function findByNationalId(string $nationalId);

    /**
     * Get all customers
     */
    public function getCustomers();

    /**
     * Get all agents
     */
    public function getAgents();

    /**
     * Create user with hashed password
     */
    public function createUser(array $data);

    /**
     * Update user password
     */
    public function updatePassword(int $userId, string $newPassword): bool;

    /**
     * Get user with orders
     */
    public function getUserWithOrders(int $userId);

    /**
     * Get user with cart
     */
    public function getUserWithCart(int $userId);

    /**
     * Get user referral codes
     */
    public function getUserReferralCodes(int $userId);
}