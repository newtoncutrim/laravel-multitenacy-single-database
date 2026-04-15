<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PlatformAdminSeeder extends Seeder
{
    public function run(): void
    {
        $superAdminRole = $this->platformRole('Super Admin', User::ROLE_SUPER_ADMIN);
        $this->platformRole('Platform Admin', User::ROLE_PLATFORM_ADMIN);
        $supportRole = $this->platformRole('Support', User::ROLE_SUPPORT);

        $this->createPlatformUser(
            env('SUPER_ADMIN_EMAIL'),
            env('SUPER_ADMIN_PASSWORD'),
            env('SUPER_ADMIN_NAME', 'Super Admin'),
            $superAdminRole
        );

        $this->createPlatformUser(
            env('SUPPORT_USER_EMAIL'),
            env('SUPPORT_USER_PASSWORD'),
            env('SUPPORT_USER_NAME', 'Support'),
            $supportRole
        );
    }

    private function platformRole(string $name, string $slug): Role
    {
        return Role::updateOrCreate(
            [
                'tenant_id' => null,
                'slug' => $slug,
            ],
            [
                'name' => $name,
                'scope' => 'platform',
                'is_system' => true,
            ]
        );
    }

    private function createPlatformUser(?string $email, ?string $password, string $name, Role $role): void
    {
        if (! $email || ! $password) {
            return;
        }

        $user = User::updateOrCreate(
            ['email' => $email],
            [
                'tenant_id' => null,
                'branch_id' => null,
                'name' => $name,
                'password' => Hash::make($password),
            ]
        );

        $user->roles()->syncWithoutDetaching([$role->id]);
    }
}
