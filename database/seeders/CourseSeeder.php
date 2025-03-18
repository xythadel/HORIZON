<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Course;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Course::factory()
            ->count(2)
            ->sequence(
                ['name' => 'Vue.js', 'description' => 'An open-source model–view–viewmodel front-end JavaScript framework for building user interfaces and single-page applications.'],
                ['name' => 'Laravel', 'description' => 'A free and open-source PHP-based web framework for building web applications'],
            )
            ->create();
    }
}
