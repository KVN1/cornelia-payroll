<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DeductionSettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            [
                'key'         => 'sss',
                'label'       => 'SSS Contribution',
                'value'       => 4.50,
                'type'        => 'percentage',
                'description' => 'Social Security System — employee share',
                'is_active'   => true,
            ],
            [
                'key'         => 'philhealth',
                'label'       => 'PhilHealth Contribution',
                'value'       => 2.00,
                'type'        => 'percentage',
                'description' => 'Philippine Health Insurance — employee share',
                'is_active'   => true,
            ],
            [
                'key'         => 'pagibig',
                'label'       => 'Pag-IBIG Contribution',
                'value'       => 2.00,
                'type'        => 'percentage',
                'description' => 'Home Development Mutual Fund — employee share',
                'is_active'   => true,
            ],
            [
                'key'         => 'tax',
                'label'       => 'Withholding Tax',
                'value'       => 0.00,
                'type'        => 'percentage',
                'description' => 'BIR withholding tax — set to 0 to disable',
                'is_active'   => true,
            ],
            [
                'key'         => 'late_deduction',
                'label'       => 'Late Deduction',
                'value'       => 1.00,
                'type'        => 'percentage',
                'description' => 'Deduction per minute late (% of daily rate)',
                'is_active'   => true,
            ],
        ];

        foreach ($settings as $setting) {
            DB::table('deduction_settings')->updateOrInsert(
                ['key' => $setting['key']],
                array_merge($setting, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
            );
        }
    }
}