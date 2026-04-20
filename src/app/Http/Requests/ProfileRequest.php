<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
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
            //
            'profile_image' => 'mimes:jpeg,png',
            'user_name' => ['required','max:20'],
            'post_code' => ['required', 'regex:/^\d{3}-\d{4}$/'],
            'address' => 'required',
        ];
    }

    public function messages()
        {
         return [
        'profile_image.mimes' => '.jpegもしくは.pngをアップロードしてください',
        'user_name.required' => 'ユーザー名を入力してください',
        'user_name.max' => 'ユーザー名は20文字以内で入力してください',
        'post_code.required' => '郵便番号を入力してください。',
        'post_code.regex' => '郵便番号は「123-4567」の形式で入力してください。',
        'address.required' => '住所を入力してください',
        ];
    }
}
