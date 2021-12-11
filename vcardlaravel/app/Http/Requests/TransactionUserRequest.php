<?php

namespace App\Http\Requests;

use App\Rules\CategoryRule;
use App\Rules\DebitRule;
use App\Rules\ReferenceRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class TransactionUserRequest extends FormRequest
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
            'payment_reference' => new ReferenceRule,
            'value' => ['required','gt:0',new DebitRule],
            'payment_type' => 'required|exists:payment_types,code',
            'category' => [new CategoryRule],
            'description' => 'nullable|max:8192',
        ];
    }

    public function messages(){
        return [
            'value.required' => 'Vcard transaction needs a value!',
            'value.gt' => 'Value of transaction needs a value superior to zero!',
            'payment_type.required' => 'Payment Type required for transaction',
            'payment_type.exists' => 'Invalid payment type. Must be one of the following: :values',
            'category.exists' => 'Invalid category. Select already existing or create a new one',
            'description.max' => 'Exceeded maximum valid description size'
        ];
    }
}
