<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Departments
        $depts = [
            ['name' => 'Front of House', 'description' => 'Cashiers, servers, customer-facing staff'],
            ['name' => 'Kitchen',        'description' => 'Cooks, prep staff, dishwashers'],
            ['name' => 'Bar',            'description' => 'Baristas and beverage staff'],
            ['name' => 'Management',     'description' => 'Supervisors and managers'],
        ];
        foreach ($depts as $dept) {
            DB::table('departments')->insertOrIgnore($dept + ['created_at' => now(), 'updated_at' => now()]);
        }

        // Positions
        $positions = [
            [1, 'Cashier',        610.00],
            [1, 'Server',         610.00],
            [2, 'Line Cook',      650.00],
            [2, 'Head Chef',      900.00],
            [2, 'Dishwasher',     575.00],
            [3, 'Barista',        635.00],
            [3, 'Senior Barista', 700.00],
            [4, 'Supervisor',     850.00],
            [4, 'Manager',       1100.00],
        ];
        foreach ($positions as [$dId, $title, $rate]) {
            DB::table('positions')->insertOrIgnore([
                'department_id'   => $dId,
                'title'           => $title,
                'base_daily_rate' => $rate,
                'created_at'      => now(),
                'updated_at'      => now(),
            ]);
        }

        // Shifts
        $shifts = [
            ['Opening Shift', '06:00:00', '14:00:00', 60],
            ['Mid Shift',     '10:00:00', '18:00:00', 60],
            ['Closing Shift', '14:00:00', '22:00:00', 60],
            ['Full Day',      '08:00:00', '17:00:00', 60],
        ];
        foreach ($shifts as [$name, $start, $end, $break]) {
            DB::table('shifts')->insertOrIgnore([
                'name'          => $name,
                'start_time'    => $start,
                'end_time'      => $end,
                'break_minutes' => $break,
                'created_at'    => now(),
                'updated_at'    => now(),
            ]);
        }

        // Leave Types
        foreach (['Sick Leave', 'Vacation Leave', 'Emergency Leave'] as $lt) {
            DB::table('leave_types')->insertOrIgnore(['name' => $lt, 'is_paid' => 1]);
        }
        DB::table('leave_types')->insertOrIgnore(['name' => 'Unpaid Leave', 'is_paid' => 0]);

        // PH Holidays 2025
        $holidays = [
            ["New Year's Day",          '2025-01-01', 'regular'],
            ['People Power Anniversary','2025-02-25', 'special_non_working'],
            ['Araw ng Kagitingan',      '2025-04-09', 'regular'],
            ['Maundy Thursday',         '2025-04-17', 'regular'],
            ['Good Friday',             '2025-04-18', 'regular'],
            ['Labor Day',               '2025-05-01', 'regular'],
            ['Independence Day',        '2025-06-12', 'regular'],
            ['Ninoy Aquino Day',        '2025-08-21', 'special_non_working'],
            ['National Heroes Day',     '2025-08-25', 'regular'],
            ['All Saints Day',          '2025-11-01', 'special_non_working'],
            ['Bonifacio Day',           '2025-11-30', 'regular'],
            ['Immaculate Conception',   '2025-12-08', 'special_non_working'],
            ['Christmas Day',           '2025-12-25', 'regular'],
            ['Rizal Day',               '2025-12-30', 'regular'],
            ["New Year's Eve",          '2025-12-31', 'special_non_working'],
        ];
        foreach ($holidays as [$name, $date, $type]) {
            DB::table('holidays')->insertOrIgnore([
                'name'         => $name,
                'holiday_date' => $date,
                'type'         => $type,
            ]);
        }

        // Sample Employee
        $posId = DB::table('positions')->where('title', 'Manager')->value('id');
        $empId = DB::table('employees')->insertGetId([
            'employee_no'     => 'CSB-001',
            'first_name'      => 'Admin',
            'last_name'       => 'User',
            'position_id'     => $posId,
            'employment_type' => 'full_time',
            'hire_date'       => '2024-01-01',
            'status'          => 'active',
            'daily_rate'      => 1100.00,
            'created_at'      => now(),
            'updated_at'      => now(),
        ]);

        // Admin user
        DB::table('users')->insertOrIgnore([
            'name'        => 'Admin User',
            'email'       => 'admin@corneliastreetbistro.com',
            'password'    => Hash::make('password'),
            'role'        => 'admin',
            'employee_id' => $empId,
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);
    }
}
