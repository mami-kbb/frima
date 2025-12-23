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
            'name' => ['required', 'string'],
            'description' => ['required', 'string',  'max:255'],
            'item_image' => ['required', 'image', 'mimes:jpeg,png'],
            'category_item' => ['required', 'array'],
            'category_item.*' => ['exists:categories,id'],
            'condition_id' => ['required'],
            'price' => ['required', 'integer', 'min:0'],
            'brand_name' => ['nullable', 'string'],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => '商品名を入力してください',
            'description.required' => '商品説明を入力してください',
            'description.max' => '商品説明は255文字以内で入力してください',
            'item_image.required' => '商品画像をアップロードしてください',
            'item_image.mimes' => '「.png」または「.jpeg」形式でアップロードしてください',
            'category_item.required' => '商品のカテゴリーを選択してください',
            'condition_id.required' => '商品の状態を選択してください',
            'price.required' => '商品価格を入力してください',
            'price.integer' => '数値で入力してください',
            'price.min' => '0円以上で入力してください'
        ];
    }
}
