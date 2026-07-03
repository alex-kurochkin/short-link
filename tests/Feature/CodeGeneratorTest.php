<?php

namespace Tests\Feature;

use App\Contracts\CodeUniquenessChecker;
use App\Services\CodeGenerator;
use Illuminate\Support\Facades\Config;
use Mockery;

test('генератор возвращает код заданной длины', function () {
    Config::set('short-links.code_length', 6);

    $checker = Mockery::mock(CodeUniquenessChecker::class);
    $checker->shouldReceive('exists')->andReturn(false);

    $generator = new CodeGenerator($checker);
    $code = $generator->generate();

    expect($code)->toHaveLength(6);
});

test('генератор использует настройку code_length из конфига', function () {
    Config::set('short-links.code_length', 8);

    $checker = Mockery::mock(CodeUniquenessChecker::class);
    $checker->shouldReceive('exists')->andReturn(false);

    $generator = new CodeGenerator($checker);
    $code = $generator->generate();

    expect($code)->toHaveLength(8);
});

test('генератор выбрасывает исключение при превышении максимального количества попыток', function () {
    Config::set('short-links.code_length', 6);

    $checker = Mockery::mock(CodeUniquenessChecker::class);
    $checker->shouldReceive('exists')->andReturn(true);

    $generator = new CodeGenerator($checker);

    expect(fn() => $generator->generate())->toThrow(\RuntimeException::class);
});

test('генератор возвращает код, который не существует в хранилище', function () {
    Config::set('short-links.code_length', 6);

    $checker = Mockery::mock(CodeUniquenessChecker::class);
    $checker->shouldReceive('exists')
        ->once()
        ->andReturn(true)
        ->shouldReceive('exists')
        ->once()
        ->andReturn(false);

    $generator = new CodeGenerator($checker);
    $code = $generator->generate();

    expect($code)->toBeString();
    expect($code)->toHaveLength(6);
});

test('генератор вызывает метод exists у checker для проверки уникальности', function () {
    Config::set('short-links.code_length', 6);

    $checker = Mockery::mock(CodeUniquenessChecker::class);
    $checker->shouldReceive('exists')
        ->once()
        ->andReturn(false);

    $generator = new CodeGenerator($checker);
    $generator->generate();
});

test('генератор делает несколько попыток при коллизиях', function () {
    Config::set('short-links.code_length', 6);

    $checker = Mockery::mock(CodeUniquenessChecker::class);
    $checker->shouldReceive('exists')
        ->times(3)
        ->andReturn(true)
        ->shouldReceive('exists')
        ->once()
        ->andReturn(false);

    $generator = new CodeGenerator($checker);
    $code = $generator->generate();

    expect($code)->toBeString();
});

test('генератор возвращает разные коды при разных вызовах', function () {
    Config::set('short-links.code_length', 6);

    $checker = Mockery::mock(CodeUniquenessChecker::class);
    $checker->shouldReceive('exists')->andReturn(false);

    $generator = new CodeGenerator($checker);

    $code1 = $generator->generate();
    $code2 = $generator->generate();

    expect($code1)->not->toBe($code2);
});
