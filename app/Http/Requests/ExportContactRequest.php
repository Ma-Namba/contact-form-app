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
            'keyword.max' => 'キーワードは255文字までです。',
            'gender.int' => '1:男性,2:女性,3:その他の数字で入力してください。',
            'page.min' => '最低値1です。',
            'per_page.min' => '1~100までを入力してください。',
            'per_page.max' => '1~100までを入力してください。',
        ];
    }
}
