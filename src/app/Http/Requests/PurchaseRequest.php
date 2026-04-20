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
            'payment' => 'required',
            'address_id' => 'nullable|exists:addresses,id',
        ];
    }

    public function messages()
        {
         return [
        'payment.required' => '支払先を入力してください',
        ];
    }

    public function withValidator($validator)
{
    $validator->after(function ($validator) {
        if (
            !$this->address_id &&
            !auth()->user()?->profile?->address
        ) {
            $validator->errors()->add('address_id', '配送先を入力してください');
        }
    });
}
}
