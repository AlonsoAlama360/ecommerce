<?php

namespace Tests\Unit\ValueObjects;

use App\Domain\User\ValueObjects\Role;
use PHPUnit\Framework\TestCase;

class RoleTest extends TestCase
{
    public function test_admin_has_admin_access(): void
    {
        $this->assertTrue(Role::Admin->hasAdminAccess());
    }

    public function test_vendedor_has_admin_access(): void
    {
        $this->assertTrue(Role::Vendedor->hasAdminAccess());
    }

    public function test_cliente_does_not_have_admin_access(): void
    {
        $this->assertFalse(Role::Cliente->hasAdminAccess());
    }

    public function test_role_values(): void
    {
        $this->assertSame('admin', Role::Admin->value);
        $this->assertSame('vendedor', Role::Vendedor->value);
        $this->assertSame('cliente', Role::Cliente->value);
    }
}
