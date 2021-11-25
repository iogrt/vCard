<?php

namespace App\Http\Requests;

use App\Rules\CategoryRule;
use App\Rules\ReferenceRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TransactionOtherRequest extends FormRequest
{
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
        //todo payment types controller
        return [
            'vcard' => 'required|digits:9',
            'value' => 'required|gt:0',
            'payment_type' => ['required','exists:payment_types,code',Rule::notIn(['VCARD'])],
            'payment_reference' => [new ReferenceRule],
            'type' => 'in:C,D',
            'category' => [new CategoryRule],
            'description' => 'nullable|max:8192',
        ];
    }

    public function messages(){
        return [
            'vcard.required' => 'Vcard transaction needs a vcard owner',
            'value.required' => 'Vcard transaction needs a value!',
            'value.min' => 'Value of transaction needs a value superior to zero!',
            'payment_type.required' => 'Payment type must exist',
            'type.required' => 'Payment type must be either credit or debit',
            'payment_type.exists' => 'Invalid payment type. Must be one of the following: :values',
            'category.exists' => 'Invalid category. Select already existing or create a new one',
            'description.max' => 'Exceeded maximum valid description size',
        ];
    }
}
