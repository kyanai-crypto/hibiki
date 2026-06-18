<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(SettingsSeeder::class);

        // 管理者アカウント（初期データ）
        User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name'              => '管理者',
                'phone'             => '090-0000-0000',
                'address'           => '未設定',
                'role'              => 'master',
                'password'          => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
    }
}
