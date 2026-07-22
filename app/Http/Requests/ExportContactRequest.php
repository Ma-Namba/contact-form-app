<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExportContactRequest extends FormRequest
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
            'category_id' => 'nullable|exists:categories,id',
            'keyword' => 'nullable|string|max:255',
            'gender' => 'nullable|integer|in:1,2,3',
            'update_date' => 'nullable|date',
            'page'=>'nullable|integer|min:1',
            'per_page'=>'nullable|integer|min:1|max:100'
        ];
    }

    public function messages()
    {
        return [
            'category_id.required' => 'お問い合わせの種類の選択は必須です。',
            'first_name.required' => '苗字は必須です。',
            'last_name.required' => '名前は必須です。',
            'gender.required' => '性別の選択は必須です。',
            'email.required' => 'メールアドレスは必須です。',
            'tel' => '電話番号は必須です。',
            'address' => '住所の入力は必須です。',
            'detail' => 'お問い合わせの内容は必須です。'
        ];
    }
}
