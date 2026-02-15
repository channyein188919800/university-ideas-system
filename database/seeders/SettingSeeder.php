<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            [
                'key' => 'academic_year',
                'value' => '2024-2025',
                'type' => 'string',
                'group' => 'closure_dates',
                'description' => 'Current academic year',
            ],
            [
                'key' => 'idea_closure_date',
                'value' => now()->addMonths(3)->endOfDay()->toDateTimeString(),
                'type' => 'datetime',
                'group' => 'closure_dates',
                'description' => 'Date when idea submission closes',
            ],
            [
                'key' => 'final_closure_date',
                'value' => now()->addMonths(4)->endOfDay()->toDateTimeString(),
                'type' => 'datetime',
                'group' => 'closure_dates',
                'description' => 'Final date when commenting closes',
            ],
        ];

        foreach ($settings as $setting) {
            Setting::create($setting);
        }
    }
}
