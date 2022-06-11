<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'required',
            'order' => 'required|numeric',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => '※ カテゴリー名を入力して下さい',
            'order.required' => '※ 順番を入力して下さい',
            'order.numeric' => '※ 順番は数字で入力して下さい',
        ];
    }
}
