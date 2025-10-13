<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleMenuAccessSeeder extends Seeder
{
    public function run(): void
    {
        // Clear existing role menu access
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('role_menu_access')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Get all existing menu_ids from menu_items table
        $existingMenuIds = DB::table('menu_items')->pluck('menu_id')->toArray();

        $this->command->info('🔍 Found ' . count($existingMenuIds) . ' menu items in database');
        $this->command->info('📋 Menu ID range: ' . min($existingMenuIds) . ' to ' . max($existingMenuIds));

        // ============================================
        // ADMIN ACCESS - Give access to admin menus (typically 1-27)
        // ============================================
        $adminMenuIds = array_filter($existingMenuIds, function($id) {
            return $id >= 1 && $id <= 27;
        });

        $adminMenus = [];
        foreach ($adminMenuIds as $menuId) {
            $adminMenus[] = [
                'role_id' => 1,
                'menu_id' => $menuId,
                'can_view' => true,
                'can_edit' => true,
                'can_delete' => true,
            ];
        }

        // ============================================
        // COMPANY ACCESS - Give access to company menus (typically 28-53)
        // ============================================
        $companyMenuIds = array_filter($existingMenuIds, function($id) {
            return $id >= 28 && $id <= 53;
        });

        $companyMenus = [];
        foreach ($companyMenuIds as $menuId) {
            $companyMenus[] = [
                'role_id' => 3,
                'menu_id' => $menuId,
                'can_view' => true,
                'can_edit' => true,
                'can_delete' => false,
            ];
        }

        // ============================================
        // FREELANCER ACCESS - Give access to freelancer menus (typically 54-73)
        // ============================================
        $freelancerMenuIds = array_filter($existingMenuIds, function($id) {
            return $id >= 54 && $id <= 73;
        });

        $freelancerMenus = [];
        foreach ($freelancerMenuIds as $menuId) {
            $freelancerMenus[] = [
                'role_id' => 2,
                'menu_id' => $menuId,
                'can_view' => true,
                'can_edit' => true,
                'can_delete' => false,
            ];
        }

        // ============================================
        // SUPPORT ACCESS - Give access to support menus (typically 74-77)
        // ============================================
        $supportMenuIds = array_filter($existingMenuIds, function($id) {
            return $id >= 74 && $id <= 77;
        });

        $supportMenus = [];
        foreach ($supportMenuIds as $menuId) {
            $supportMenus[] = [
                'role_id' => 4,
                'menu_id' => $menuId,
                'can_view' => true,
                'can_edit' => true,
                'can_delete' => false,
            ];
        }

        // Merge all menu access arrays
        $roleMenuAccess = array_merge($adminMenus, $companyMenus, $freelancerMenus, $supportMenus);

        // Insert role menu access with timestamps
        foreach ($roleMenuAccess as $access) {
            DB::table('role_menu_access')->insert(array_merge($access, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }

        $this->command->info('✅ Successfully seeded role menu access!');
        $this->command->info('   - Admin: Access to ' . count($adminMenuIds) . ' menu items');
        $this->command->info('   - Company: Access to ' . count($companyMenuIds) . ' menu items');
        $this->command->info('   - Freelancer: Access to ' . count($freelancerMenuIds) . ' menu items');
        $this->command->info('   - Support: Access to ' . count($supportMenuIds) . ' menu items');
        $this->command->info('📊 Total role menu access entries: ' . count($roleMenuAccess));

        // Show any missing menu IDs in expected ranges
        $expectedAdminIds = range(1, 27);
        $missingAdminIds = array_diff($expectedAdminIds, $adminMenuIds);
        if (!empty($missingAdminIds)) {
            $this->command->warn('⚠️  Missing Admin menu IDs: ' . implode(', ', $missingAdminIds));
        }
    }
}
