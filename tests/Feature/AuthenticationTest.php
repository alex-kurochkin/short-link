<?php

use App\Models\User;

test('пользователь может зарегистрироваться', function () {
    $response = $this->post('/register', [
        'name' => 'Тестовый Пользователь',
        'email' => 'test@example.com',
        'password' => 'secret123',
        'password_confirmation' => 'secret123',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect('/admin');

    $this->assertDatabaseHas('users', [
        'email' => 'test@example.com',
    ]);
});

test('пользователь не может зарегистрироваться с невалидными данными', function () {
    $response = $this->post('/register', [
        'name' => '',
        'email' => 'not-an-email',
        'password' => 'short',
        'password_confirmation' => 'mismatch',
    ]);

    $this->assertGuest();
    $response->assertSessionHasErrors(['name', 'email', 'password']);
});

test('пользователь может войти в систему', function () {
    $user = User::factory()->create([
        'email' => 'user@example.com',
        'password' => bcrypt('secret123'),
    ]);

    $response = $this->post('/login', [
        'email' => 'user@example.com',
        'password' => 'secret123',
    ]);

    $this->assertAuthenticatedAs($user);
    $response->assertRedirect('/admin');
});

test('пользователь не может войти с неверным паролем', function () {
    $user = User::factory()->create([
        'password' => bcrypt('secret123'),
    ]);

    $response = $this->post('/login', [
        'email' => $user->email,
        'password' => 'wrong-password',
    ]);

    $this->assertGuest();
    $response->assertSessionHasErrors('email');
});

test('пользователь может выйти из системы', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $response = $this->post('/logout');

    $this->assertGuest();
    $response->assertRedirect('/login');
});
