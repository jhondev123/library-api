<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class UsernameValidation implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $regex = "/^[A-Za-zÀ-ÿ]+([  ][A-Za-zÀ-ÿ]+)*$/";
        if(!preg_match( $regex , $value)) {
            $fail("O campo $attribute deve conter apenas letras e espaços.");
        }

    }
}
