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
             'phone_number' => 'required',//falta: campo unique, ter exatamente 9 'letras'
             'name' => 'required|regex:/^([a-zA-Z]+)(\s[a-zA-Z]+)*$/',//falta:ter mais q 3 letras
             'email' => 'required',//falta:ser do tipo email maybe
             'password' => 'required',//falta: ter mais q 6 letras
             'photo_url' => 'nullable|image|max:8192',
             'confirmation_code' => 'required'//falta:ter exatamente 4 numeros
        ];
    }

    public function messages() {
        return [
            'phone_number.required' => 'Phone number is mandatory',
            'name.regex' => 'Name should just have letters',
            'name.required' => 'Name is mandatory',
            'password.required' => 'Password is mandatory',
            'email.required' => 'Email is mandatory',
            'confirmation_code.required' => 'Confirmation code is mandatory'
        ];
    }
}
