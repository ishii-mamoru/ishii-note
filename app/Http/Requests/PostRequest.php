<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostRequest extends FormRequest
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
            'status' => 'required|numeric',
            'category' => 'nullable|array',
            'post_date' => 'required|date',
            'title' => 'required|max:255',
            'subtitle' => 'required|max:255',
        ];
    }

    public function messages()
    {
        return [
            'status.required' => '※ ステータスを選択して下さい',
            'status.numeric' => '※ ステータスを選択して下さい',
            'category.array' => '※ カテゴリーを選択して下さい',
            'post_date.required' => '※ 投稿日時を入力して下さい',
            'post_date.date' => '※ 投稿日時を入力して下さい',
            'title.required' => '※ タイトルを入力して下さい',
            'title.max' => '※ タイトルは255字以下で入力して下さい',
            'subtitle.required' => '※ サブタイトルを入力して下さい',
            'subtitle.max' => '※ サブタイトルは255字以下で入力して下さい', 
        ];
    }
}
