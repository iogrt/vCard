<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class EditProfileRequest extends FormRequest
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
            'name' => 'string|alpha',
            'email' => 'email',
            'password' => Password::min(6),
            'photo_url' => 'nullable|image|max:8192',
        ];
    }

    public function messages(){
        return [
            'name.*' => 'name must be alphanumeric string',
            'email.email' => 'must be valid email',
        ];
    }
}
