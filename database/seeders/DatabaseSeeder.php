<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            ['name' => 'QMS Administrator', 'email' => 'admin@qms.test', 'password' => 'password'],
            ['name' => 'Yahya Al Naaimi', 'email' => 'yahya.alnaaimi@qms.test', 'password' => 'Yahya@2026'],
            ['name' => 'Mazin Al Farsi', 'email' => 'mazin.alfarsi@qms.test', 'password' => 'Mazin@2026'],
            ['name' => 'Aisha Al Balushi', 'email' => 'aisha.albalushi@qms.test', 'password' => 'Dummy@2026'],
            ['name' => 'Omar Al Harthy', 'email' => 'omar.alharthy@qms.test', 'password' => 'Dummy@2026'],
        ];

        foreach ($users as $user) {
            User::updateOrCreate(
                ['email' => $user['email']],
                [
                    'name' => $user['name'],
                    'password' => Hash::make($user['password']),
                ]
            );
        }

        $this->call(QmsPrototypeSeeder::class);
    }
}
