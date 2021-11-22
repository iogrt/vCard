<?php

namespace App\Http\Requests;

use App\Rules\CategoryRule;
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
            'vcard' => 'required|between:900000000,999999999',
            'pair_vcard' => 'required|between:900000000,999999999',
            'value' => 'required|gt:0',
            //'payment_type' => 'exists:payment_types,name', always VCARD here
            'category' => [new CategoryRule],
            'description' => 'nullable|max:8192',
        ];
    }

    public function messages(){
        return [
            'vcard.required' => 'Vcard transaction needs a vcard owner',
            'pair_vcard.required' => 'Vcard transaction needs a destination vcard',
            'value.required' => 'Vcard transaction needs a value!',
            'value.min' => 'Value of transaction needs a value superior to zero!',
            //'payment_type.exists' => 'Invalid payment type. Must be one of the following: :values',
            'category.exists' => 'Invalid category. Select already existing or create a new one',
            'description.max' => 'Exceeded maximum valid description size',
        ];
    }
}
