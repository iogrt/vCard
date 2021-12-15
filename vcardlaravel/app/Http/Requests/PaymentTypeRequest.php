<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaymentTypeRequest extends FormRequest
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
            'code' => 'required|unique:payment_types,code',
            'name' => 'required|string',
            'description' => 'nullable|string|max:8192',
            'validation_rules' => 'nullable'
        ];
    }

    public function messages(){
        return [
            'code.required' => 'Code required to create new payment type',
            'code.unique' => 'Payment Type Code must not be present already',
            'name.required' => 'Name required to create new payment type',
            'name.string' => 'Name must be a UTF8 string',
            'description.max' => 'Description maximum size is 8192 :)',
        ];
    }
}
