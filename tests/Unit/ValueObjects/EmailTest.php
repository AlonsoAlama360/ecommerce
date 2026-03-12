<?php

namespace Tests\Unit\ValueObjects;

use App\Domain\User\ValueObjects\Email;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class EmailTest extends TestCase
{
    public function test_creates_valid_email(): void
    {
        $email = new Email('user@example.com');

        $this->assertSame('user@example.com', $email->value);
    }

    public function test_normalizes_to_lowercase(): void
    {
        $email = new Email('User@EXAMPLE.COM');

        $this->assertSame('user@example.com', $email->value);
    }

    public function test_trims_whitespace(): void
    {
        $email = new Email('  user@example.com  ');

        $this->assertSame('user@example.com', $email->value);
    }

    public function test_throws_on_invalid_email(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new Email('not-an-email');
    }

    public function test_throws_on_empty_string(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new Email('');
    }

    public function test_equals_returns_true_for_same_email(): void
    {
        $email1 = new Email('user@example.com');
        $email2 = new Email('User@Example.COM');

        $this->assertTrue($email1->equals($email2));
    }

    public function test_equals_returns_false_for_different_emails(): void
    {
        $email1 = new Email('user@example.com');
        $email2 = new Email('other@example.com');

        $this->assertFalse($email1->equals($email2));
    }

    public function test_to_string_returns_value(): void
    {
        $email = new Email('user@example.com');

        $this->assertSame('user@example.com', (string) $email);
    }
}
