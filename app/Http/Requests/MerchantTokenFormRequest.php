<?php

namespace App\Http\Requests;

use App\Rules\AmountRule;
use App\Rules\CurrencyRule;
use App\Rules\PhoneNumberRule;
use App\Rules\SecureUrlRule;

class MerchantTokenFormRequest extends FormRequest 
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
            'customer.*' => 'required',
            'customer.email' => 'required|email|max:255',
            'customer.first_name' => 'required|string|min:3|max:100',
            'customer.last_name' => 'required|string|min:3|max:100',
            'customer.phone_number' => ['required', 'min:10', 'max:12', new PhoneNumberRule],
            'customer.username' => 'required|string|min:3',
            'customer.account_number' => 'required',
            'currency' => ['required', 'string', 'size:3', new CurrencyRule],
            'amount' => ['required', 'digits_between:1,12', new AmountRule],
            'order_id' => 'required',
            'description' => 'max:255',
            'redirect_url' => ['required', 'url', new SecureUrlRule],
            'callback_url' => ['required', 'url', new SecureUrlRule],

        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
       return [];
    }
}