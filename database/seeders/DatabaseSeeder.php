<?php

namespace Database\Seeders;

use App\Models\QmsDepartment;
use App\Models\QmsLocation;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $departments = [
            ['name' => 'Safety', 'code' => 'SAF', 'manager_name' => 'Yahya Al Naaimi'],
            ['name' => 'Quality', 'code' => 'QLT', 'manager_name' => 'Mazin Al Farsi'],
            ['name' => 'HSE', 'code' => 'HSE', 'manager_name' => 'Aisha Al Balushi'],
            ['name' => 'Engineering', 'code' => 'ENG', 'manager_name' => 'Omar Al Harthy'],
        ];

        foreach ($departments as $department) {
            QmsDepartment::updateOrCreate(['code' => $department['code']], $department + ['active' => true]);
        }

        foreach ([
            ['name' => 'OQB Locations', 'code' => 'OQB', 'type' => 'Station'],
            ['name' => 'Muscat HQ', 'code' => 'MCT-HQ', 'type' => 'Office'],
            ['name' => 'Engineering Workshop', 'code' => 'ENG-WS', 'type' => 'Operational Area'],
        ] as $location) {
            QmsLocation::updateOrCreate(['code' => $location['code']], $location + ['active' => true]);
        }

        $safety = QmsDepartment::where('code', 'SAF')->first();
        $quality = QmsDepartment::where('code', 'QLT')->first();
        $hse = QmsDepartment::where('code', 'HSE')->first();
        $engineering = QmsDepartment::where('code', 'ENG')->first();

        $users = [
            ['name' => 'QMS Administrator', 'email' => 'admin@qms.test', 'password' => 'password', 'qms_role' => 'Super Admin', 'department_id' => $quality?->id, 'job_title' => 'Platform Administrator'],
            ['name' => 'Yahya Al Naaimi', 'email' => 'yahya.alnaaimi@qms.test', 'password' => 'Yahya@2026', 'qms_role' => 'Safety Admin', 'department_id' => $safety?->id, 'job_title' => 'Safety Manager'],
            ['name' => 'Mazin Al Farsi', 'email' => 'mazin.alfarsi@qms.test', 'password' => 'Mazin@2026', 'qms_role' => 'Quality Admin', 'department_id' => $quality?->id, 'job_title' => 'Quality Manager'],
            ['name' => 'Aisha Al Balushi', 'email' => 'aisha.albalushi@qms.test', 'password' => 'Dummy@2026', 'qms_role' => 'HSE Admin', 'department_id' => $hse?->id, 'job_title' => 'HSE Specialist'],
            ['name' => 'Omar Al Harthy', 'email' => 'omar.alharthy@qms.test', 'password' => 'Dummy@2026', 'qms_role' => 'Action User', 'department_id' => $engineering?->id, 'job_title' => 'Engineering Supervisor'],
        ];

        foreach ($users as $user) {
            User::updateOrCreate(
                ['email' => $user['email']],
                [
                    'name' => $user['name'],
                    'password' => Hash::make($user['password']),
                    'qms_role' => $user['qms_role'],
                    'department_id' => $user['department_id'],
                    'job_title' => $user['job_title'],
                    'is_active' => true,
                ]
            );
        }

        $this->call(QmsPrototypeSeeder::class);
    }
}
