<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            ['name' => 'Siti Nuraini (Super Admin)', 'email' => 'admin@jkptg.demo', 'role' => 'super-admin'],
            ['name' => 'Ahmad Faris (Editor)', 'email' => 'editor@jkptg.demo', 'role' => 'editor'],
            ['name' => 'Norazian (Viewer)', 'email' => 'viewer@jkptg.demo', 'role' => 'viewer'],
            ['name' => 'Hafiz Rahman (Citizen)', 'email' => 'citizen@jkptg.demo', 'role' => 'citizen'],
        ];

        foreach ($users as $u) {
            $user = User::updateOrCreate(
                ['email' => $u['email']],
                [
                    'name' => $u['name'],
                    'password' => bcrypt('password'),
                    'email_verified_at' => now(),
                ]
            );
            $user->syncRoles([$u['role']]);
        }
    }
}
