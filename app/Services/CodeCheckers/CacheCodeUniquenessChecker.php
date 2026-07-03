<?php

namespace App\Services\CodeCheckers;

use App\Contracts\CodeUniquenessChecker;
use Illuminate\Support\Facades\Cache;

class CacheCodeUniquenessChecker implements CodeUniquenessChecker
{
    /**
     * Чтобы использовать эту реализацию,
     * нужно ещё слушать событие создания Link и писать код в кэш,
     * а при удалении очищать.
     * Поскольку это тестовое задание, в проекте этого нет.
     */
    public function exists(string $code): bool
    {
        return Cache::has("short_link_code_{$code}");
    }
}
