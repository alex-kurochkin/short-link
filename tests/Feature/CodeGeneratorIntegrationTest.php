<?php

use App\Models\Link;
use App\Models\User;
use App\Services\CodeGenerator;

test('генератор создаёт уникальный код в реальной БД', function () {
    $user = User::factory()->create();

    // Создаём ссылку с известным кодом
    Link::create([
        'user_id' => $user->id,
        'code' => 'aaaaaa',
        'original_url' => 'https://example.com',
    ]);

    $generator = app(CodeGenerator::class);
    $code = $generator->generate();

    // Код не должен совпадать с существующим
    expect($code)->not->toBe('aaaaaa');

    // Код должен быть уникальным
    expect(Link::where('code', $code)->exists())->toBeFalse();
});
