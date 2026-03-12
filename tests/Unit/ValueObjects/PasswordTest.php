<?php

namespace Tests\Unit\ValueObjects;

use App\Domain\User\ValueObjects\Password;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class PasswordTest extends TestCase
{
    public function test_creates_valid_password(): void
    {
        $password = new Password('Secret1234');

        $this->assertSame('Secret1234', $password->value);
    }

    public function test_throws_on_short_password(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('al menos 8 caracteres');

        new Password('Ab1');
    }

    public function test_throws_without_uppercase(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('mayúsculas y minúsculas');

        new Password('lowercase123');
    }

    public function test_throws_without_lowercase(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('mayúsculas y minúsculas');

        new Password('UPPERCASE123');
    }

    public function test_throws_without_number(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('al menos un número');

        new Password('SecretPassword');
    }
}
