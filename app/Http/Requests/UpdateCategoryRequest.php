<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
               'name' => 'sometimes|required|string|max:255',
                'description' => 'nullable|string',
                'parent_id' => 'nullable|exists:categories,id',
                'image' => 'nullable|image|max:2048',
        ];
    }

        public function messages(): array
    {
        return [
            'name.required' => 'اسم الفئة مطلوب.',
            'name.string' => 'اسم الفئة يجب أن يكون نصاً.',
            'name.max' => 'اسم الفئة لا يمكن أن يتجاوز 255 حرفاً.',
            'description.string' => 'الوصف يجب أن يكون نصاً.',
            'parent_id.exists' => 'الفئة الأب المحددة غير موجودة.',
            'image.image' => 'الملف المرفق يجب أن يكون صورة.',
            'image.max' => 'حجم الصورة لا يمكن أن يتجاوز 2 ميجابايت.',
        ];
    }
}

