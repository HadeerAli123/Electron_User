<?php

namespace App\Interfaces;
use Illuminate\Http\Request;

interface  InstallmentRequestRepositoryInterface extends BaseRepositoryInterface
{
 public function getAllPlans();

    /**
     * Get installment plan with products
     */
    public function getPlanWithProducts(int $planId);

    /**
     * Get product installment plans
     */
    public function getProductPlans(int $productId);

    /**
     * Create installment request
     */
    public function createRequest(array $data);

    /**
     * Get installment request by ID
     */
    public function getRequest(int $requestId);

    /**
     * Get installment requests by status
     */
    public function getRequestsByStatus(string $status);

    /**
     * Approve installment request
     */
    public function approveRequest(int $requestId): bool;

    /**
     * Reject installment request
     */
    public function rejectRequest(int $requestId): bool;

    /**
     * Get user installment requests
     */
    public function getUserRequests(int $userId);

    /**
     * Calculate installment details
     */
    public function calculateInstallment(int $planId, float $productPrice): array;

    /**
     * Get pending requests
     */
    public function getPendingRequests();

    /**
     * Verify referral code
     */
    public function verifyReferralCode(string $code );
}