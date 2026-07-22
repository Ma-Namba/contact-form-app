<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
class StoreContactRequest extends FormRequest
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
            'category_id' => 'required|exists:categories,id',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'gender' => 'required|integer|in:1,2,3',
            'email' => 'required|email|max:255',
            'tel' => 'required|string|regex:/^[0-9]{10,11}$/',
            'address' => 'required|string|max:255',
            'building' => 'nullable|string|max:255',
            'detail' => 'required|string|max:120',
            'tag_ids' => 'nullable|array',
            'tag_ids.' => 'integer|exists:tags,id',
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
            'detail' => 'お問い合わせの内容は必須です。',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        // エラー時は強制的に422のJSONレスポンスを投げる
        throw new HttpResponseException(response()->json([
            'status' => 'error',
            'errors' => $validator->errors()
        ], 422));
    }
}
