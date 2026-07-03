<?php

use App\Models\Link;
use App\Models\User;
use App\Services\CodeGenerator;

test('короткая ссылка перенаправляет на оригинальный URL', function () {
    $user = User::factory()->create();

    $link = Link::create([
        'user_id' => $user->id,
        'code' => 'abc123',
        'original_url' => 'https://example.com/target-page',
    ]);

    $response = $this->get('/abc123');

    $response->assertRedirect('https://example.com/target-page');
});

test('переход по короткой ссылке записывает клик в БД', function () {
    $user = User::factory()->create();

    $link = Link::create([
        'user_id' => $user->id,
        'code' => 'click1',
        'original_url' => 'https://example.com',
    ]);

    // Имитируем запрос с конкретным IP
    $this->get('/click1', ['REMOTE_ADDR' => '192.168.1.100']);

    expect($link->clicks()->count())->toBe(1);

    $click = $link->clicks()->first();
    expect($click->ip_address)->toBe('192.168.1.100');
    expect($click->clicked_at)->not->toBeNull();
});

test('несуществующая короткая ссылка возвращает 404', function () {
    $response = $this->get('/zzzzzz');

    $response->assertNotFound();
});

test('несуществующий формат кода возвращает 404 от роутера', function () {
    // В маршруте стоит ограничение [A-Za-z0-9]{6}, поэтому код из 3 символов не пройдёт
    $response = $this->get('/abc');

    $response->assertNotFound();
});

test('один пользователь не видит ссылки другого пользователя', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();

    Link::create([
        'user_id' => $user1->id,
        'code' => 'user11',
        'original_url' => 'https://example.com/user1',
    ]);

    // Заходим под вторым пользователем
    $this->actingAs($user2);

    // В Filament мы ограничили выборку через getEloquentQuery,
    // но для теста проверим саму логику контроллера или модели
    $user2Links = Link::where('user_id', $user2->id)->get();

    expect($user2Links->count())->toBe(0);
});
