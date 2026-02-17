<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreInstallmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'order_id'            => 'required|exists:orders,id',
            'installment_plan_id' => 'required|exists:installment_plans,id',
            'monthly_salary'      => 'required|numeric|min:0',
            'referral_code'       => 'nullable|string|exists:referral_codes,code',
        ];
    }

    public function messages(): array
    {
        return [
            'order_id.required' => 'رقم الطلب مطلوب.',
            'order_id.exists'   => 'الطلب غير موجود.',

            'installment_plan_id.required' => 'يجب اختيار خطة التقسيط.',
            'installment_plan_id.exists'   => 'خطة التقسيط غير موجودة.',

            'monthly_salary.required' => 'الراتب الشهري مطلوب.',
            'monthly_salary.numeric'  => 'الراتب الشهري يجب أن يكون رقم.',
            'monthly_salary.min'      => 'الراتب الشهري لا يمكن أن يكون أقل من صفر.',

            'referral_code.string' => 'كود الإحالة غير صالح.',
            'referral_code.exists' => 'كود الإحالة غير صحيح أو غير موجود.',
        ];
    }
}
