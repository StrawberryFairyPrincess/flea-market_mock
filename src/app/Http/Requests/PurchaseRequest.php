<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PurchaseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'payment' => ['required'],
            'address' => ['required']
        ];
    }

    // 表示するエラー文を設定
    public function messages()
    {
        return [
            // バリデーションに引っかかったら$errorsに格納される
            'payment.required' => '支払い方法を選択してください',
            'address.required' => '住所を選択してください'
        ];
    }
}
