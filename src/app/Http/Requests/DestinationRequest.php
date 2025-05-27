<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DestinationRequest extends FormRequest
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
            'name' => ['required'],
            'post_code' => ['required', 'regex:/^\d{3}-\d{4}$/'],
            'address' => ['required'],
            'building' => [],
            'img_pass' => ['mimes:jpg,jpeg,png']
        ];
    }

    // 表示するエラー文の設定
    public function messages()
    {
        return [
            // バリデーションに引っかかったら$errorsに格納される
            'name.required' => 'お名前を入力してください',
            'post_code.required' => '郵便番号を入力してください',
            'post_code.regex' => '郵便番号は「3桁の数字」、「-(ハイフン)」、「4桁の数
            数字」で入力してください',
            'address.required' => '住所を入力してください',
            'img_pass.mimes' => '画像は.jpegか.pngを選択してください'
        ];
    }
}
