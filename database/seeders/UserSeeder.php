<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $departments = Department::all();
        $faker = Faker::create();
        $emailDomain = env('SEED_EMAIL_DOMAIN', 'example.com');

        // Admin User (only if none exists)
        if (!User::where('role', 'admin')->exists()) {
            User::create([
                'name' => 'System Administrator',
                'email' => env('SEED_ADMIN_EMAIL', "admin@{$emailDomain}"),
                'password' => Hash::make(env('SEED_DEFAULT_PASSWORD', 'password')),
                'role' => 'admin',
                'department_id' => $departments->first()->id,
                'terms_accepted' => true,
                'terms_accepted_at' => now(),
            ]);
        }

        // QA Manager (only if none exists)
        if (!User::where('role', 'qa_manager')->exists()) {
            User::create([
                'name' => 'QA Manager',
                'email' => env('SEED_QA_MANAGER_EMAIL', "qamanager@{$emailDomain}"),
                'password' => Hash::make(env('SEED_DEFAULT_PASSWORD', 'password')),
                'role' => 'qa_manager',
                'department_id' => $departments->first()->id,
                'terms_accepted' => true,
                'terms_accepted_at' => now(),
            ]);
        }

        // QA Coordinators (one per department)
        foreach ($departments as $index => $department) {
            $existingCoordinator = User::where('role', 'qa_coordinator')
                ->where('department_id', $department->id)
                ->first();

            if ($existingCoordinator) {
                if (!$department->qa_coordinator_id) {
                    $department->qa_coordinator_id = $existingCoordinator->id;
                    $department->save();
                }
                continue;
            }

            $user = User::create([
                'name' => 'QA Coordinator ' . ($index + 1),
                'email' => "qacoordinator" . ($index + 1) . "@{$emailDomain}",
                'password' => Hash::make(env('SEED_DEFAULT_PASSWORD', 'password')),
                'role' => 'qa_coordinator',
                'department_id' => $department->id,
                'terms_accepted' => true,
                'terms_accepted_at' => now(),
            ]);

            // Update department with QA coordinator
            $department->qa_coordinator_id = $user->id;
            $department->save();
        }

        // Staff Users (only if none exist)
        if (!User::where('role', 'staff')->exists()) {
            $staffCount = (int) env('SEED_STAFF_COUNT', 5);
            for ($i = 1; $i <= $staffCount; $i++) {
                User::create([
                    'name' => 'Staff Member ' . $i,
                    'email' => $faker->unique()->userName . "@{$emailDomain}",
                    'password' => Hash::make(env('SEED_DEFAULT_PASSWORD', 'password')),
                    'role' => 'staff',
                    'department_id' => $departments->random()->id,
                    'terms_accepted' => true,
                    'terms_accepted_at' => now(),
                ]);
            }
        }
    }
}
