<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'national_id' => [
                'required',
                'string',
                'size:14',
                'regex:/^[0-9]{14}$/', // فقط أرقام
            ],
            'password' => [
                'required',
                'string',
                'min:8',
            ],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            // National ID messages
            'national_id.required' => 'الرقم القومي مطلوب',
            'national_id.string' => 'الرقم القومي يجب أن يكون نص',
            'national_id.size' => 'الرقم القومي يجب أن يكون 14 رقم',
            'national_id.regex' => 'الرقم القومي يجب أن يحتوي على أرقام فقط',
            
            // Password messages
            'password.required' => 'كلمة المرور مطلوبة',
            'password.string' => 'كلمة المرور يجب أن تكون نص',
            'password.min' => 'كلمة المرور يجب أن تكون 8 أحرف على الأقل',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'national_id' => 'الرقم القومي',
            'password' => 'كلمة المرور',
        ];
    }

    /**
     * Handle a failed validation attempt.
     */
    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        throw new \Illuminate\Validation\ValidationException($validator, response()->json([
            'success' => false,
            'message' => 'فشل التحقق من البيانات',
            'errors' => $validator->errors(),
        ], 422));
    }
}