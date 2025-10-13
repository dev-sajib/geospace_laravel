<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolePermissionsTableSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [];
        
        // Admin role (role_id = 1) - Full permissions for menus 1-27
        for ($menuId = 1; $menuId <= 27; $menuId++) {
            if ($menuId == 24 || $menuId == 25) continue; // Skip non-existent IDs
            $permissions[] = [
                'role_id' => 1,
                'menu_id' => $menuId,
                'can_view' => 1,
                'can_create' => 1,
                'can_edit' => 1,
                'can_delete' => 1,
                'created_at' => '2025-10-02 20:18:53'
            ];
        }
        
        // Company role (role_id = 3) - Menus 28-53
        $companyPermissions = [
            ['menu_id' => 28, 'can_view' => 1, 'can_create' => 0, 'can_edit' => 0, 'can_delete' => 0],
            ['menu_id' => 29, 'can_view' => 1, 'can_create' => 1, 'can_edit' => 1, 'can_delete' => 0],
            ['menu_id' => 30, 'can_view' => 1, 'can_create' => 1, 'can_edit' => 1, 'can_delete' => 0],
            ['menu_id' => 31, 'can_view' => 1, 'can_create' => 0, 'can_edit' => 0, 'can_delete' => 0],
            ['menu_id' => 32, 'can_view' => 1, 'can_create' => 0, 'can_edit' => 1, 'can_delete' => 0],
            ['menu_id' => 33, 'can_view' => 1, 'can_create' => 0, 'can_edit' => 0, 'can_delete' => 0],
            ['menu_id' => 34, 'can_view' => 1, 'can_create' => 0, 'can_edit' => 0, 'can_delete' => 0],
            ['menu_id' => 35, 'can_view' => 1, 'can_create' => 0, 'can_edit' => 0, 'can_delete' => 0],
            ['menu_id' => 36, 'can_view' => 1, 'can_create' => 1, 'can_edit' => 1, 'can_delete' => 0],
            ['menu_id' => 37, 'can_view' => 1, 'can_create' => 1, 'can_edit' => 1, 'can_delete' => 0],
            ['menu_id' => 38, 'can_view' => 1, 'can_create' => 1, 'can_edit' => 1, 'can_delete' => 0],
            ['menu_id' => 39, 'can_view' => 1, 'can_create' => 0, 'can_edit' => 0, 'can_delete' => 0],
            ['menu_id' => 40, 'can_view' => 1, 'can_create' => 0, 'can_edit' => 0, 'can_delete' => 0],
            ['menu_id' => 41, 'can_view' => 1, 'can_create' => 0, 'can_edit' => 1, 'can_delete' => 0],
            ['menu_id' => 42, 'can_view' => 1, 'can_create' => 0, 'can_edit' => 1, 'can_delete' => 0],
            ['menu_id' => 43, 'can_view' => 1, 'can_create' => 1, 'can_edit' => 1, 'can_delete' => 1],
            ['menu_id' => 44, 'can_view' => 1, 'can_create' => 0, 'can_edit' => 1, 'can_delete' => 0],
            ['menu_id' => 45, 'can_view' => 1, 'can_create' => 0, 'can_edit' => 0, 'can_delete' => 0],
            ['menu_id' => 46, 'can_view' => 1, 'can_create' => 0, 'can_edit' => 1, 'can_delete' => 0],
            ['menu_id' => 47, 'can_view' => 1, 'can_create' => 0, 'can_edit' => 0, 'can_delete' => 0],
            ['menu_id' => 48, 'can_view' => 1, 'can_create' => 0, 'can_edit' => 0, 'can_delete' => 0],
            ['menu_id' => 49, 'can_view' => 1, 'can_create' => 0, 'can_edit' => 0, 'can_delete' => 0],
            ['menu_id' => 50, 'can_view' => 1, 'can_create' => 0, 'can_edit' => 0, 'can_delete' => 0],
            ['menu_id' => 51, 'can_view' => 1, 'can_create' => 1, 'can_edit' => 1, 'can_delete' => 0],
            ['menu_id' => 52, 'can_view' => 1, 'can_create' => 0, 'can_edit' => 0, 'can_delete' => 0],
            ['menu_id' => 53, 'can_view' => 1, 'can_create' => 0, 'can_edit' => 1, 'can_delete' => 0],
        ];
        
        foreach ($companyPermissions as $perm) {
            $permissions[] = array_merge([
                'role_id' => 3,
                'created_at' => '2025-10-02 20:19:36'
            ], $perm);
        }
        
        // Freelancer role (role_id = 2) - Menus 54-73
        $freelancerPermissions = [
            ['menu_id' => 54, 'can_view' => 1, 'can_create' => 0, 'can_edit' => 0, 'can_delete' => 0],
            ['menu_id' => 55, 'can_view' => 1, 'can_create' => 0, 'can_edit' => 0, 'can_delete' => 0],
            ['menu_id' => 56, 'can_view' => 1, 'can_create' => 0, 'can_edit' => 0, 'can_delete' => 0],
            ['menu_id' => 57, 'can_view' => 1, 'can_create' => 0, 'can_edit' => 0, 'can_delete' => 0],
            ['menu_id' => 58, 'can_view' => 1, 'can_create' => 0, 'can_edit' => 0, 'can_delete' => 0],
            ['menu_id' => 59, 'can_view' => 1, 'can_create' => 0, 'can_edit' => 0, 'can_delete' => 0],
            ['menu_id' => 60, 'can_view' => 1, 'can_create' => 0, 'can_edit' => 0, 'can_delete' => 0],
            ['menu_id' => 61, 'can_view' => 1, 'can_create' => 1, 'can_edit' => 1, 'can_delete' => 1],
            ['menu_id' => 62, 'can_view' => 1, 'can_create' => 1, 'can_edit' => 1, 'can_delete' => 0],
            ['menu_id' => 63, 'can_view' => 1, 'can_create' => 1, 'can_edit' => 1, 'can_delete' => 0],
            ['menu_id' => 64, 'can_view' => 1, 'can_create' => 0, 'can_edit' => 1, 'can_delete' => 0],
            ['menu_id' => 65, 'can_view' => 1, 'can_create' => 0, 'can_edit' => 1, 'can_delete' => 0],
            ['menu_id' => 66, 'can_view' => 1, 'can_create' => 0, 'can_edit' => 0, 'can_delete' => 0],
            ['menu_id' => 67, 'can_view' => 1, 'can_create' => 0, 'can_edit' => 0, 'can_delete' => 0],
            ['menu_id' => 68, 'can_view' => 1, 'can_create' => 0, 'can_edit' => 0, 'can_delete' => 0],
            ['menu_id' => 69, 'can_view' => 1, 'can_create' => 0, 'can_edit' => 0, 'can_delete' => 0],
            ['menu_id' => 70, 'can_view' => 1, 'can_create' => 0, 'can_edit' => 0, 'can_delete' => 0],
            ['menu_id' => 71, 'can_view' => 1, 'can_create' => 0, 'can_edit' => 0, 'can_delete' => 0],
            ['menu_id' => 72, 'can_view' => 1, 'can_create' => 0, 'can_edit' => 1, 'can_delete' => 0],
            ['menu_id' => 73, 'can_view' => 1, 'can_create' => 1, 'can_edit' => 1, 'can_delete' => 0],
        ];
        
        foreach ($freelancerPermissions as $perm) {
            $permissions[] = array_merge([
                'role_id' => 2,
                'created_at' => '2025-10-02 20:19:36'
            ], $perm);
        }
        
        // Support role (role_id = 4) - Menus 74-77
        $supportPermissions = [
            ['menu_id' => 74, 'can_view' => 1, 'can_create' => 0, 'can_edit' => 0, 'can_delete' => 0],
            ['menu_id' => 75, 'can_view' => 1, 'can_create' => 1, 'can_edit' => 1, 'can_delete' => 0],
            ['menu_id' => 76, 'can_view' => 1, 'can_create' => 1, 'can_edit' => 1, 'can_delete' => 0],
            ['menu_id' => 77, 'can_view' => 1, 'can_create' => 1, 'can_edit' => 1, 'can_delete' => 0],
        ];
        
        foreach ($supportPermissions as $perm) {
            $permissions[] = array_merge([
                'role_id' => 4,
                'created_at' => '2025-10-02 20:19:36'
            ], $perm);
        }
        
        DB::table('role_permissions')->insert($permissions);
    }
}
