<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\InstallmentRequestRepository;
use App\Http\Requests\StoreInstallmentRequest;
class InstallmentRequestController extends Controller
{
     protected $installmentRepo;

    public function __construct(InstallmentRequestRepository $installmentRepo)
    {
        $this->installmentRepo = $installmentRepo;
    }


        public function getAllPlans()
    {
        try {
            $plans = $this->installmentRepo->getAllPlans();

            return response()->json([
                'success' => true,
                'data' => $plans,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }



  public function getProductPlans($productId)
    {
        try {
            $plans = $this->installmentRepo->getProductPlans($productId);

            return response()->json([
                'success' => true,
                'data' => $plans,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 404);
        }
    } 




     public function storeRequest(StoreInstallmentRequest $request)
    {
        try {

            $installmentRequest = $this->installmentRepo->createRequest(
                $request->user()->id,
                $request->only(['order_id', 'installment_plan_id', 'monthly_salary']),
                $request->referral_code
            );

            return response()->json([
                'success' => true,
                'message' => 'تم تقديم طلب التقسيط بنجاح، سيتم مراجعته قريباً',
                'data'    => $installmentRequest,
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }


}
