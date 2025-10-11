<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleMenuAccessSeeder extends Seeder
{
    public function run(): void
    {
        $roleMenuAccess = [
            // Admin access (full access to admin menus)
            ['role_id' => 1, 'menu_id' => 1, 'can_view' => true, 'can_edit' => true, 'can_delete' => true],
            ['role_id' => 1, 'menu_id' => 2, 'can_view' => true, 'can_edit' => true, 'can_delete' => true],
            ['role_id' => 1, 'menu_id' => 3, 'can_view' => true, 'can_edit' => true, 'can_delete' => true],
            ['role_id' => 1, 'menu_id' => 4, 'can_view' => true, 'can_edit' => true, 'can_delete' => true],
            ['role_id' => 1, 'menu_id' => 5, 'can_view' => true, 'can_edit' => true, 'can_delete' => true],
            ['role_id' => 1, 'menu_id' => 6, 'can_view' => true, 'can_edit' => true, 'can_delete' => true],
            ['role_id' => 1, 'menu_id' => 7, 'can_view' => true, 'can_edit' => true, 'can_delete' => true],
            ['role_id' => 1, 'menu_id' => 8, 'can_view' => true, 'can_edit' => true, 'can_delete' => true],
            ['role_id' => 1, 'menu_id' => 9, 'can_view' => true, 'can_edit' => true, 'can_delete' => true],
            ['role_id' => 1, 'menu_id' => 10, 'can_view' => true, 'can_edit' => true, 'can_delete' => true],

            // Company access (company menus)
            ['role_id' => 3, 'menu_id' => 20, 'can_view' => true, 'can_edit' => true, 'can_delete' => false],
            ['role_id' => 3, 'menu_id' => 21, 'can_view' => true, 'can_edit' => true, 'can_delete' => false],
            ['role_id' => 3, 'menu_id' => 22, 'can_view' => true, 'can_edit' => true, 'can_delete' => false],
            ['role_id' => 3, 'menu_id' => 23, 'can_view' => true, 'can_edit' => true, 'can_delete' => false],
            ['role_id' => 3, 'menu_id' => 24, 'can_view' => true, 'can_edit' => true, 'can_delete' => false],
            ['role_id' => 3, 'menu_id' => 25, 'can_view' => true, 'can_edit' => true, 'can_delete' => false],
            ['role_id' => 3, 'menu_id' => 26, 'can_view' => true, 'can_edit' => true, 'can_delete' => false],
            ['role_id' => 3, 'menu_id' => 27, 'can_view' => true, 'can_edit' => false, 'can_delete' => false],

            // Freelancer access (freelancer menus)
            ['role_id' => 2, 'menu_id' => 40, 'can_view' => true, 'can_edit' => true, 'can_delete' => false],
            ['role_id' => 2, 'menu_id' => 41, 'can_view' => true, 'can_edit' => true, 'can_delete' => false],
            ['role_id' => 2, 'menu_id' => 42, 'can_view' => true, 'can_edit' => true, 'can_delete' => false],
            ['role_id' => 2, 'menu_id' => 43, 'can_view' => true, 'can_edit' => true, 'can_delete' => false],
            ['role_id' => 2, 'menu_id' => 44, 'can_view' => true, 'can_edit' => false, 'can_delete' => false],
            ['role_id' => 2, 'menu_id' => 45, 'can_view' => true, 'can_edit' => false, 'can_delete' => false],
            ['role_id' => 2, 'menu_id' => 46, 'can_view' => true, 'can_edit' => false, 'can_delete' => false],
        ];

        foreach ($roleMenuAccess as $access) {
            DB::table('role_menu_access')->insert(array_merge($access, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
