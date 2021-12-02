<?php

namespace App\Rules;

use App\Models\Vcard;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class DebitRule implements Rule
{
    private $errorMsg;
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
        if($attribute != 'value') return false;

        $vcard = Vcard::find(Auth::user()->username);

        if($value <= 0){
            $this->errorMsg = 'Transaction value must be greater than 0';
            return false;
        }

        if($vcard->max_debit < $value && request()->type == 'D'){
            $this->errorMsg = 'Max debit is' . $vcard->max_debit;
            return false;
        }

        if($vcard->balance < $value && request()->type == 'D'){
            $this->errorMsg = 'You are too poor to transfer that amount';
            return false;
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->errorMsg;
    }
}
