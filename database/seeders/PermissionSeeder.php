<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin_role = Role::updateOrCreate([
            'name' => 'admin'
        ]);

        $customer_role = Role::updateOrCreate([
            'name' => 'customer'
        ]);

        $permission_admin = Permission::updateOrCreate([
            'name' => 'view_dashboard'
        ]);

        $permission_customer = Permission::updateOrCreate([
            'name' => 'view_product'
        ]);

        $admin_role->givePermissionTo($permission_admin);
        $customer_role->givePermissionTo($permission_customer);

        $user = User::find(2);
        $user->assignRole(['customer']);
    }
}
