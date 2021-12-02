<?php

namespace App\Rules;

use App\Models\PaymentType;
use Illuminate\Contracts\Validation\Rule;

class ReferenceRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if($value == null || !request()->has('payment_type')){
            return false;
        }

        $type = request()->get('payment_type');
        $payment_type = PaymentType::find($type);

        //é um hack, mas as regras de validaçao estão contidas em regex
        $reg = json_decode($payment_type->validation_rules);

        if($reg == '' || !$reg){
            return true;
        }

        return preg_match($reg,$value) == 1;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Invalid payment reference';
    }
}
