<?php

namespace App\Services;

use App\Contracts\CodeUniquenessChecker;
use Illuminate\Support\Str;

class CodeGenerator
{
    public function __construct(
        private readonly CodeUniquenessChecker $checker
    ) {}

    public function generate(): string
    {
        $length = config('short-links.code_length');
        $maxAttempts = 10;
        $attempts = 0;

        do {
            $code = Str::random($length);
            $attempts++;
        } while ($this->checker->exists($code) && $attempts < $maxAttempts);

        if ($attempts >= $maxAttempts) {
            throw new \RuntimeException('Не удалось сгенерировать уникальный код');
        }

        return $code;
    }
}
