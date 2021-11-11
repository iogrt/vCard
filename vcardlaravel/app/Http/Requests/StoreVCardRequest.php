<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreVCardRequest extends FormRequest
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
            'phone_number' => 'required|digits:9',//campo unique em falta
            'name' => 'required',//regex:/^[A-Za-záàâãéèêíóôõúçÁÀÂÃÉÈÍÓÔÕÚÇ ]+$/
            'email' => 'required|email',
            'password' => 'required',
            'photo_url' => 'nullable|image|max:8192',
            'confirmation_code' => 'required|digits:4'
        ];
    }

    /*public function messages() {
        return [
            'phone_number.required' => 'phone number is mandatory',
            'phone_number.digits' => 'phone number should have 9 numbers',
            'name.regex' => 'name should just have letter',
            'name.required' => 'name is mandatory',
            'email.required' => 'email is required',
            'confirmation_code.required' => 'confirmation code is mandatory'
        ];
    }*/
}
