<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        $departments = [
            [
                'name' => 'Computer Science',
                'code' => 'CS',
                'description' => 'Department of Computer Science and Information Technology',
            ],
            [
                'name' => 'Business Administration',
                'code' => 'BUS',
                'description' => 'School of Business and Management',
            ],
            [
                'name' => 'Engineering',
                'code' => 'ENG',
                'description' => 'Faculty of Engineering',
            ],
            [
                'name' => 'Arts and Humanities',
                'code' => 'AH',
                'description' => 'Department of Arts and Humanities',
            ],
            [
                'name' => 'Science',
                'code' => 'SCI',
                'description' => 'Faculty of Science',
            ],
            [
                'name' => 'Medicine',
                'code' => 'MED',
                'description' => 'School of Medicine and Health Sciences',
            ],
            [
                'name' => 'Law',
                'code' => 'LAW',
                'description' => 'School of Law',
            ],
            [
                'name' => 'Education',
                'code' => 'EDU',
                'description' => 'School of Education',
            ],
        ];

        foreach ($departments as $department) {
            Department::create($department);
        }
    }
}
