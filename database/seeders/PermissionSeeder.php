<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $modules = [
            'Dashboard' => [
                'dashboard.view' => 'Ver Dashboard',
            ],
            'Usuarios' => [
                'users.view' => 'Ver usuarios',
                'users.create' => 'Crear usuarios',
                'users.edit' => 'Editar usuarios',
                'users.delete' => 'Eliminar usuarios',
            ],
            'Categorías' => [
                'categories.view' => 'Ver categorías',
                'categories.create' => 'Crear categorías',
                'categories.edit' => 'Editar categorías',
                'categories.delete' => 'Eliminar categorías',
            ],
            'Productos' => [
                'products.view' => 'Ver productos',
                'products.create' => 'Crear productos',
                'products.edit' => 'Editar productos',
                'products.delete' => 'Eliminar productos',
            ],
            'Proveedores' => [
                'suppliers.view' => 'Ver proveedores',
                'suppliers.create' => 'Crear proveedores',
                'suppliers.edit' => 'Editar proveedores',
                'suppliers.delete' => 'Eliminar proveedores',
            ],
            'Ventas' => [
                'orders.view' => 'Ver ventas',
                'orders.create' => 'Crear ventas',
                'orders.edit' => 'Editar ventas',
                'orders.delete' => 'Eliminar ventas',
                'orders.export' => 'Exportar ventas',
            ],
            'Compras' => [
                'purchases.view' => 'Ver compras',
                'purchases.create' => 'Crear compras',
                'purchases.edit' => 'Editar compras',
                'purchases.delete' => 'Eliminar compras',
            ],
            'Kardex' => [
                'kardex.view' => 'Ver kardex',
                'kardex.adjust' => 'Ajustar stock',
                'kardex.export' => 'Exportar kardex',
            ],
            'Lista de Deseos' => [
                'wishlists.view' => 'Ver listas de deseos',
                'wishlists.export' => 'Exportar listas de deseos',
            ],
            'Reseñas' => [
                'reviews.view' => 'Ver reseñas',
                'reviews.moderate' => 'Moderar reseñas',
            ],
            'Suscriptores' => [
                'subscribers.view' => 'Ver suscriptores',
                'subscribers.delete' => 'Eliminar suscriptores',
                'subscribers.export' => 'Exportar suscriptores',
            ],
            'Reclamaciones' => [
                'complaints.view' => 'Ver reclamaciones',
                'complaints.respond' => 'Responder reclamaciones',
            ],
            'Mensajes de Contacto' => [
                'contact_messages.view' => 'Ver mensajes de contacto',
                'contact_messages.respond' => 'Responder mensajes',
                'contact_messages.delete' => 'Eliminar mensajes',
            ],
            'Reportes' => [
                'reports.view' => 'Ver reportes',
            ],
            'Configuración' => [
                'settings.view' => 'Ver configuración',
                'settings.edit' => 'Editar configuración',
            ],
            'Roles y Permisos' => [
                'roles.view' => 'Ver roles y permisos',
                'roles.edit' => 'Editar roles y permisos',
            ],
        ];

        // Create system roles
        Role::firstOrCreate(['name' => 'admin'], [
            'display_name' => 'Administrador',
            'description' => 'Acceso total al sistema',
            'is_admin' => true,
            'is_system' => true,
        ]);
        Role::firstOrCreate(['name' => 'vendedor'], [
            'display_name' => 'Vendedor',
            'description' => 'Acceso limitado según permisos asignados',
            'is_admin' => true,
            'is_system' => true,
        ]);
        Role::firstOrCreate(['name' => 'cliente'], [
            'display_name' => 'Cliente',
            'description' => 'Usuario de la tienda',
            'is_admin' => false,
            'is_system' => true,
        ]);

        // Create all permissions
        $allPermissionIds = [];
        foreach ($modules as $module => $permissions) {
            foreach ($permissions as $name => $displayName) {
                $permission = Permission::firstOrCreate(
                    ['name' => $name],
                    ['display_name' => $displayName, 'module' => $module]
                );
                $allPermissionIds[] = $permission->id;
            }
        }

        // Admin gets all permissions
        DB::table('role_has_permissions')->where('role', 'admin')->delete();
        $adminInserts = array_map(fn($id) => ['role' => 'admin', 'permission_id' => $id], $allPermissionIds);
        DB::table('role_has_permissions')->insert($adminInserts);

        // Vendedor gets limited permissions
        $vendedorPermissions = [
            'dashboard.view',
            'products.view',
            'categories.view',
            'orders.view', 'orders.create', 'orders.edit', 'orders.export',
            'kardex.view',
            'wishlists.view',
            'reviews.view',
            'complaints.view',
            'contact_messages.view',
        ];

        $vendedorIds = Permission::whereIn('name', $vendedorPermissions)->pluck('id')->toArray();
        DB::table('role_has_permissions')->where('role', 'vendedor')->delete();
        $vendedorInserts = array_map(fn($id) => ['role' => 'vendedor', 'permission_id' => $id], $vendedorIds);
        DB::table('role_has_permissions')->insert($vendedorInserts);
    }
}
