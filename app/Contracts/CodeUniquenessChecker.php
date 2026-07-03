<?php

namespace App\Contracts;

interface CodeUniquenessChecker
{
    /**
     * Проверить, существует ли код в хранилище
     */
    public function exists(string $code): bool;
}
