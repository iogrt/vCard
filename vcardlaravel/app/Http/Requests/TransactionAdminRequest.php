<?php

namespace App\Http\Requests;

use App\Rules\CategoryRule;
use App\Rules\DebitRule;
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
            'vcard' => 'required|digits:9',
            'payment_reference' => 'required|digits:9',
            'value' => ['required','gt:0',new DebitRule],
            'payment_type' => 'required|exists:payment_types,code|not_in:VCARD',
            'category' => 'exclude',
            'type' => [new TransactionTypeRule],
            'description' => 'nullable|max:8192',
        ];
    }

    public function messages(){
        return ['payment_reference.required' => 'Vcard transaction needs a destination vcard',
            'value.required' => 'Vcard transaction needs a value!',
            'value.min' => 'Value of transaction needs a value superior to zero!',
            'payment_type.required' => 'Payment Type required for transaction',
            'payment_type.exists' => 'Invalid payment type. Must be one of the following: :values',
            'category.exists' => 'Invalid category. Select already existing or create a new one',
            'description.max' => 'Exceeded maximum valid description size'
        ];
    }
}
