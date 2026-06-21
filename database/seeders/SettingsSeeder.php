<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            [
                'key'         => 'default_capacity',
                'value'       => '10',
                'description' => '基本定員（名）',
            ],
            [
                'key'         => 'capacity_threshold_few',
                'value'       => '80',
                'description' => '残りわずか（△）判定の閾値（%）',
            ],
            [
                'key'         => 'morning_open',
                'value'       => '1',
                'description' => '午前便 デフォルト営業',
            ],
            [
                'key'         => 'afternoon_open',
                'value'       => '0',
                'description' => '午後便 デフォルト営業',
            ],
            [
                'key'         => 'night_open',
                'value'       => '0',
                'description' => '夜便 デフォルト営業',
            ],
            [
                'key'         => 'line_channel_access_token',
                'value'       => '',
                'description' => 'LINE Channel Access Token',
            ],
            [
                'key'         => 'line_admin_user_id',
                'value'       => '',
                'description' => '管理者の LINE User ID',
            ],
            [
                'key'         => 'price_info',
                'value'       => '',
                'description' => '料金案内（HTML可）',
            ],
            [
                'key'         => 'site_name',
                'value'       => '響丸　遊漁船予約',
                'description' => 'サイト名',
            ],
        ];

        foreach ($settings as $setting) {
            DB::table('settings')->updateOrInsert(
                ['key' => $setting['key']],
                array_merge($setting, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
            );
        }
    }
}
