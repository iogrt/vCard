<?php

namespace App\Rules;

use App\Models\Category;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class CategoryRule implements Rule
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
        if($value == null || $value == ''){
            return true;
        }

        return count( Category::where('vcard',Auth::user()->username)
                    ->where('name','like',$value)
                    ->where('type','like','D')
                    ->whereNull('deleted_at')->get()) > 0;

    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'No valid category under vcard with the same transaction type';
    }
}
