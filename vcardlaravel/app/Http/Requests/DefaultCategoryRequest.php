<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DefaultCategoryRequest extends FormRequest
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
            'type' => 'required|in:C,D',
            'name' => 'required|unique:default_categories,name',
        ];
    }

    public function messages(){
        return [
            'type.required' => 'Required the type of the category',
            'type.in' => 'Type must be either credit or debit',
            'name.required' => 'Required the name of the category',
        ];
    }
}
