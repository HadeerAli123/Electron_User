<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\InstallmentRequestRepository;
use App\Http\Requests\StoreInstallmentRequest;
use App\Http\Requests\ReferralCodeRequest;
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
    $data = $request->only([
        'order_id',
        'installment_plan_id',
        'monthly_salary',
        'referral_code'
    ]);

    $data['user_id'] = $request->user()->id;

    $installmentRequest = $this->installmentRepo->createRequest($data);

    return response()->json([
        'success' => true,
        'message' => 'تم تقديم طلب التقسيط بنجاح، سيتم مراجعته قريباً',
        'data'    => $installmentRequest,
    ], 201);
}

public function verifyReferralCode(ReferralCodeRequest $request)
{
    try {
        // هنا الفاليديشن اتعمل تلقائي قبل ما يوصل للكنترولر

        $isValid = $this->installmentRepo->verifyReferralCode($request->code);

        return response()->json([
            'success' => true,
            'valid'   => $isValid,
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => $e->getMessage(),
        ], 400);
    }
}
    }

