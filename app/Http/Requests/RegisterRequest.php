<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
      public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'national_id' => 'required|string|size:14|unique:users',
            'password' => 'required|string|min:8|confirmed',
'phone' => [
    'required',
    'string',
    'max:15',
    'regex:/^[0-9]{10,15}$/',
],
            'role' => 'sometimes|in:customer,agent',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'الاسم مطلوب',
            'email.required' => 'البريد الإلكتروني مطلوب',
            'email.email' => 'البريد الإلكتروني غير صحيح',
            'email.unique' => 'البريد الإلكتروني مستخدم بالفعل',
            'national_id.required' => 'الرقم القومي مطلوب',
            'national_id.size' => 'الرقم القومي يجب أن يكون 14 رقم',
            'national_id.unique' => 'الرقم القومي مستخدم بالفعل',
            'password.required' => 'كلمة المرور مطلوبة',
            'password.min' => 'كلمة المرور يجب أن تكون 8 أحرف على الأقل',
            'password.confirmed' => 'كلمة المرور غير متطابقة',
            'phone.required' => 'رقم الهاتف مطلوب',
'phone.regex' => 'رقم الهاتف غير صحيح ويجب أن يكون بين 10 و15 رقم',

        ];
    }
}