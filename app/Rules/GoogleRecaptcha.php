<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Http;
use Throwable;

class GoogleRecaptcha implements ValidationRule
{
    private function getCaptchaVerificationUrl(string $value): string
    {
        return sprintf(
            'https://www.google.com/recaptcha/api/siteverify?secret=%s&response=%s',
            $value,
            env('GOOGLE_CAPTCHA_SECRET_KEY'),
        );
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $result = false;

        try {
            $result = Http::get($this->getCaptchaVerificationUrl($value))->json('success') === true;
        } catch (Throwable $ex) {
        }

        if ($result === false) {
            $fail(__('auth.captcha_verification_failed'));
        }
    }
}
