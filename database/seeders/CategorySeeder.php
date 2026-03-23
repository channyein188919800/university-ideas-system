<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Infrastructure',
                'description' => 'Ideas related to buildings, facilities, and physical infrastructure',
            ],
            [
                'name' => 'Teaching & Learning',
                'description' => 'Ideas to improve teaching methods and learning experiences',
            ],
            [
                'name' => 'Research',
                'description' => 'Ideas to enhance research capabilities and opportunities',
            ],
            [
                'name' => 'Student Experience',
                'description' => 'Ideas to improve student life and experience',
            ],
            [
                'name' => 'Staff Welfare',
                'description' => 'Ideas related to staff wellbeing and working conditions',
            ],
            [
                'name' => 'Technology',
                'description' => 'Ideas related to IT systems and digital transformation',
            ],
            [
                'name' => 'Sustainability',
                'description' => 'Ideas for environmental sustainability and green initiatives',
            ],
            [
                'name' => 'Administration',
                'description' => 'Ideas to improve administrative processes and efficiency',
            ],
            [
                'name' => 'Library Services',
                'description' => 'Ideas to enhance library and information services',
            ],
            [
                'name' => 'International',
                'description' => 'Ideas related to international partnerships and global engagement',
            ],
                        [
                'name' => 'Education',
                'description' => 'Ideas related to curriculum development, teaching methods, and student learning experiences',
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
