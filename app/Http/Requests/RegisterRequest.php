<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
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
        'name' => [
            'required',
            'string',
            'min:3',
            'max:255',
        ],
        'national_id' => [
            'required',
            'string',
            'size:14',
            'regex:/^[0-9]{14}$/',
            'unique:users,national_id',
        ],
        'email' => [
            'required',
            'string',
            'email',
            'max:255',
            'unique:users,email',
        ],
        'password' => [
            'required',
            'string',
            'min:8',
            'confirmed',
        ],
        'role' => [
            'sometimes',
            'in:customer,agent',
        ],
        'phone' => [
            'nullable',
            'string',
            'regex:/^[0-9]{10,15}$/',
        ],
    ];

    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            // Name messages
            'name.required' => 'الاسم مطلوب',
            'name.string' => 'الاسم يجب أن يكون نص',
            'name.min' => 'الاسم يجب أن يكون 3 أحرف على الأقل',
            'name.max' => 'الاسم يجب ألا يتجاوز 255 حرف',
            'name.regex' => 'الاسم يجب أن يحتوي على حروف عربية فقط',
            
            // National ID messages
            'national_id.required' => 'الرقم القومي مطلوب',
            'national_id.string' => 'الرقم القومي يجب أن يكون نص',
            'national_id.size' => 'الرقم القومي يجب أن يكون 14 رقم',
            'national_id.regex' => 'الرقم القومي يجب أن يحتوي على أرقام فقط',
            'national_id.unique' => 'الرقم القومي مستخدم من قبل',
            
            // Email messages
            'email.required' => 'البريد الإلكتروني مطلوب',
            'email.string' => 'البريد الإلكتروني يجب أن يكون نص',
            'email.email' => 'البريد الإلكتروني غير صحيح',
            'email.max' => 'البريد الإلكتروني يجب ألا يتجاوز 255 حرف',
            'email.unique' => 'البريد الإلكتروني مستخدم من قبل',
            
            // Password messages
            'password.required' => 'كلمة المرور مطلوبة',
            'password.string' => 'كلمة المرور يجب أن تكون نص',
            'password.min' => 'كلمة المرور يجب أن تكون 8 أحرف على الأقل',
            'password.confirmed' => 'كلمة المرور غير متطابقة',
            
            // Phone messages
            'phone.string' => 'رقم الهاتف يجب أن يكون نص',
            'phone.regex' => 'رقم الهاتف غير صحيح (يجب أن يبدأ بـ 010 أو 011 أو 012 أو 015)',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'name' => 'الاسم',
            'national_id' => 'الرقم القومي',
            'email' => 'البريد الإلكتروني',
            'password' => 'كلمة المرور',
            'phone' => 'رقم الهاتف',
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

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation()
    {
        // تنظيف البيانات قبل الـ Validation
        $this->merge([
            'national_id' => trim($this->national_id ?? ''),
            'email' => trim(strtolower($this->email ?? '')),
        ]);
    }
}