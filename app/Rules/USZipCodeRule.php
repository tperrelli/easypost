<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class USZipCodeRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!preg_match('/^\d{5}(-\d{4})?$/', $value)) {
            $fail($this->message(), null);
        }
    }

    public function message(): string
    {
        return 'The :attribute must be a valid US ZIP code (12345 or 12345-6789).';
    }
}