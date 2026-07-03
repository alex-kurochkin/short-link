<?php

use App\Models\Click;
use App\Models\Link;
use App\Models\User;

test('ссылка принадлежит пользователю', function () {
    $user = User::factory()->create();
    $link = Link::factory()->create(['user_id' => $user->id]);

    expect($link->user)->toBeInstanceOf(User::class);
    expect($link->user->id)->toBe($user->id);
});

test('ссылка имеет много кликов', function () {
    $link = Link::factory()->create();

    Click::create([
        'link_id' => $link->id,
        'ip_address' => '127.0.0.1',
        'clicked_at' => now(),
    ]);
    Click::create([
        'link_id' => $link->id,
        'ip_address' => '192.168.1.1',
        'clicked_at' => now(),
    ]);

    expect($link->clicks)->toHaveCount(2);
});

test('аксессор short_url возвращает правильный формат', function () {
    $link = Link::factory()->create(['code' => 'test12']);

    // url() в тестах по умолчанию возвращает http://localhost
    expect($link->short_url)->toBe('http://localhost/test12');
});

test('аксессор clicks_count возвращает количество кликов', function () {
    $link = Link::factory()->create();

    Click::create([
        'link_id' => $link->id,
        'ip_address' => '127.0.0.1',
        'clicked_at' => now(),
    ]);

    expect($link->clicks_count)->toBe(1);
});
