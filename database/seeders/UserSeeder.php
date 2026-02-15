<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $departments = Department::all();

        // Admin User
        User::create([
            'name' => 'System Administrator',
            'email' => 'admin@university.ac.uk',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'department_id' => $departments->first()->id,
            'terms_accepted' => true,
            'terms_accepted_at' => now(),
        ]);

        // QA Manager
        User::create([
            'name' => 'QA Manager',
            'email' => 'qamanager@university.ac.uk',
            'password' => Hash::make('password'),
            'role' => 'qa_manager',
            'department_id' => $departments->first()->id,
            'terms_accepted' => true,
            'terms_accepted_at' => now(),
        ]);

        // QA Coordinators (one per department)
        foreach ($departments as $index => $department) {
            $user = User::create([
                'name' => 'QA Coordinator ' . ($index + 1),
                'email' => 'qacoordinator' . ($index + 1) . '@university.ac.uk',
                'password' => Hash::make('password'),
                'role' => 'qa_coordinator',
                'department_id' => $department->id,
                'terms_accepted' => true,
                'terms_accepted_at' => now(),
            ]);
            
            // Update department with QA coordinator
            $department->qa_coordinator_id = $user->id;
            $department->save();
        }

        // Staff Users
        $staffEmails = [
            'staff@university.ac.uk',
            'staff2@university.ac.uk',
            'staff3@university.ac.uk',
            'staff4@university.ac.uk',
            'staff5@university.ac.uk',
        ];

        foreach ($staffEmails as $index => $email) {
            User::create([
                'name' => 'Staff Member ' . ($index + 1),
                'email' => $email,
                'password' => Hash::make('password'),
                'role' => 'staff',
                'department_id' => $departments->random()->id,
                'terms_accepted' => true,
                'terms_accepted_at' => now(),
            ]);
        }
    }
}
