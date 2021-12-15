<?php

namespace App\Rules;

use App\Models\PaymentType;
use App\Models\Vcard;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class ReferenceRule implements Rule
{

    private $error = 'Payment Reference Validation Error';

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
            $this->error = "No Payment Reference";

            return false;
        }

        $type = request()->get('payment_type');

        if($type == 'VCARD'){
            if(mb_strlen($value) != 9){
                $this->error = 'Digit length must be 9 for vcard transactions';
                return false;
            }

            if($value == Auth::user()->username){
                $this->error = 'Can\'t transfer money to yourself';
                return false;
            }

            if(Vcard::find($value) == null){
                $this->error = 'Invalid vcard';
                return false;
            }

            return true;
        }

        $payment_type = PaymentType::find(request()->payment_type);

        //é um hack, mas as regras de validaçao supostamente estão contidas em regex
        $reg = json_decode($payment_type->validation_rules);

        if($reg == '' || !$reg){
            return true;
        }

        return preg_match("/$reg/",$value) != 0;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->error;
    }
}
