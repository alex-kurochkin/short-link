<?php

namespace App\Services\CodeCheckers;

use App\Contracts\CodeUniquenessChecker;
use App\Models\Link;

class EloquentCodeUniquenessChecker implements CodeUniquenessChecker
{
    public function exists(string $code): bool
    {
        return Link::where('code', $code)->exists();
    }
}
