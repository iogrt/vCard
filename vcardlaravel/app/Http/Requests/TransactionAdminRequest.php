<?php

namespace App\Http\Requests;

use App\Rules\CategoryRule;
use App\Rules\DebitRule;
use App\Rules\ReferenceRule;
use App\Rules\TransactionTypeRule;
use Illuminate\Foundation\Http\FormRequest;

class TransactionAdminRequest extends FormRequest
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
            'payment_type' => 'required|not_in:VCARD|exists:payment_types,code',
            'vcard' => 'required|digits:9',
            'payment_reference' => new ReferenceRule,
            'value' => ['required','gt:0'],
            'category' => 'exclude',
            'description' => 'nullable|max:8192',
        ];
    }

    public function messages(){
        return [
            'value.required' => 'Vcard transaction needs a value!',
            'value.min' => 'Value of transaction needs a value superior to zero!',
            'payment_type.required' => 'Payment Type required for transaction',
            'payment_type.not_in' => 'Only vcard users can do transactions between vcards',
            'payment_type.exists' => 'Invalid payment type. Must be one of the following: :values',
            'category.exclue' => 'Admins may not specify category',
            'description.max' => 'Exceeded maximum valid description size'
        ];
    }
}
