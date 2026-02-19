<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReferralCodeRequest extends FormRequest
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
            'code'        => 'required|string|max:255|unique:referral_codes,code',
            'user_id'     => 'required|exists:users,id',
            'usage_limit' => 'nullable|integer|min:1',
            'usage_count' => 'nullable|integer|min:0',
            'is_active'   => 'required|boolean',
        ];
    }

    /**
     * Custom messages for validation
     */
    public function messages(): array
    {
        return [
            'code.required'        => 'كود الإحالة مطلوب.',
            'code.string'          => 'كود الإحالة يجب أن يكون نص.',
            'code.max'             => 'كود الإحالة طويل جدًا.',
            'code.unique'          => 'كود الإحالة موجود مسبقًا.',

            'user_id.required'     => 'المستخدم مطلوب.',
            'user_id.exists'       => 'المستخدم غير موجود.',

            'usage_limit.integer'  => 'حد الاستخدام يجب أن يكون رقم صحيح.',
            'usage_limit.min'      => 'حد الاستخدام يجب أن يكون على الأقل 1.',

            'usage_count.integer'  => 'عدد الاستخدام يجب أن يكون رقم صحيح.',
            'usage_count.min'      => 'عدد الاستخدام لا يمكن أن يكون أقل من 0.',

            'is_active.required'   => 'حالة الكود مطلوبة.',
            'is_active.boolean'    => 'حالة الكود يجب أن تكون 0 أو 1.',
        ];
    }
}

