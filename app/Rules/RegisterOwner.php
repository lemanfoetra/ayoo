<?php

namespace App\Rules;

use App\User;
use Illuminate\Contracts\Validation\Rule;

class RegisterOwner implements Rule
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
        if ($attribute == "email") {

            $result = User::where('email', $value)
                ->where('role_id', '3')
                ->first();

            if ($result == null) {
                return true;
            }
        }
        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return ':attribute has been registered.';
    }
}