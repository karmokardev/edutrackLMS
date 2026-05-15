<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Cache clear করো (Spatie এর জন্য দরকার)
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

         // সব Permission তৈরি করো
        $permissions = [
            'view-users', 'create-users', 'edit-users', 'delete-users',
            'view-courses', 'create-courses', 'edit-courses', 'delete-courses',
            'publish-courses', 'assign-courses',
            'create-assessments', 'view-reports',
            'access-chat',
            'manage-subscriptions',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Role তৈরি করো
        $admin    = Role::firstOrCreate(['name' => 'Admin']);
        $trainer  = Role::firstOrCreate(['name' => 'Trainer']);
        $employee = Role::firstOrCreate(['name' => 'Employee']);

        // Admin পাবে সব permission
        $admin->givePermissionTo(Permission::all());

        // Trainer পাবে এগুলো
        $trainer->givePermissionTo([
            'view-courses', 'create-courses', 'edit-courses', 'publish-courses',
            'create-assessments', 'view-reports', 'access-chat',
        ]);

        // Employee পাবে এগুলো
        $employee->givePermissionTo([
            'view-courses', 'access-chat',
        ]);

        // Default Admin User তৈরি করো
        $user = User::firstOrCreate(
            ['email' => 'hridoy@gmail.com'],
            [
                'name'     => 'Super Admin',
                'password' => Hash::make('12345678rj'),
            ]
        );

        $user->assignRole('Admin');

        $this->command->info('✅ Roles, Permissions & Admin user তৈরি হয়েছে!');
    }
}