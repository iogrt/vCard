<?php

namespace App\Http\Requests;

use App\Rules\CategoryRule;
use App\Rules\DebitRule;
use App\Rules\TransactionTypeRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TransactionRequest extends FormRequest
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
            'value' => ['required','gt:0'],
            'payment_type' => 'required|exists:payment_types,code',
            'type' => ['required','in:C,D',new TransactionTypeRule],
            'category' => [new CategoryRule],
            'description' => 'nullable|max:8192',
        ];
    }

    public function messages(){
        return [
            'vcard.required' => 'Vcard transaction needs a vcard owner',
            'payment_reference.required' => 'Vcard transaction needs a destination vcard',
            'value.required' => 'Vcard transaction needs a value!',
            'value.min' => 'Value of transaction needs a value superior to zero!',
            'payment_type.required' => 'Payment Type required for transaction',
            'payment_type.exists' => 'Invalid payment type. Must be one of the following: :values',
            'type.in' => 'Type must be debit for vcard',
            'category.exists' => 'Invalid category. Select already existing or create a new one',
            'description.max' => 'Exceeded maximum valid description size',
        ];
    }
}
