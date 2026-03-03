<?php

namespace App\Domain\User\ValueObjects;

use InvalidArgumentException;

final class Password
{
    public readonly string $value;

    public function __construct(string $value)
    {
        if (strlen($value) < 8) {
            throw new InvalidArgumentException('La contraseña debe tener al menos 8 caracteres.');
        }

        if (!preg_match('/[a-z]/', $value) || !preg_match('/[A-Z]/', $value)) {
            throw new InvalidArgumentException('La contraseña debe contener mayúsculas y minúsculas.');
        }

        if (!preg_match('/[0-9]/', $value)) {
            throw new InvalidArgumentException('La contraseña debe contener al menos un número.');
        }

        $this->value = $value;
    }
}
