<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class UpdateTransactionRequest extends FormRequest
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
            'description' => ['nullable','max:50|min:3'],
            'category_id' => ['nullable',Rule::exists('categories','id')->where(fn($qry) => $qry->where('vcard',Auth::user()->username)->whereNull('deleted_at'))]
        ];
    }

    public function messages(){
        return [
            'description.max' => 'Exceeded maximum valid description size',
            'description.min' => 'Not Exceeded mini valid description size',
            'category_id.exists' => 'Invalid category. Select already existing or create a new one',
        ];
    }
}
