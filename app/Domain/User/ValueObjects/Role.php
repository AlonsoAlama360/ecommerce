<?php

namespace App\Domain\User\ValueObjects;

enum Role: string
{
    case Admin = 'admin';
    case Vendedor = 'vendedor';
    case Cliente = 'cliente';

    public function hasAdminAccess(): bool
    {
        return in_array($this, [self::Admin, self::Vendedor]);
    }
}
