<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExhibitionRequest extends FormRequest
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
            'brand' => [],
            'describe' => ['required', 'max:255'],
            'price' => ['required', 'integer', 'min:0'],
            'category_id' => ['required'],
            'condition_id' => ['required'],
            'img_url' => ['required', 'mimes:jpg,jpeg,png']
        ];
    }

    // 表示するエラー文の設定
    public function messages()
    {
        return [
            // バリデーションに引っかかったら$errorsに格納される
            'name.required' => '商品名を入力してください',
            'describe.required' => '商品の説明を入力してください',
            'describe.max' => '商品の説明は255文字以内で入力してください',
            'price.required' => '販売価格を入力してください',
            'price.integer' => '販売価格は整数で入力してください',
            'price.min' => '販売価格は0円以上で入力してください',
            'category_id.required' => 'カテゴリーを選択してください',
            'condition_id.required' => '商品の状態を選択してください',
            'img_url.required' => '画像を選択してください',
            'img_url.mimes' => '画像は.jpegか.pngを選択してください'
        ];
    }
}
