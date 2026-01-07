<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Course;
use App\Models\Module;
use App\Models\Lesson;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@rightpath.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        // Create sample student
        User::create([
            'name' => 'Student User',
            'email' => 'student@rightpath.com',
            'password' => Hash::make('password'),
            'role' => 'student',
            'email_verified_at' => now(),
        ]);

        // Create sample course
        $course = Course::create([
            'title' => 'Introduction to Web Development',
            'slug' => 'intro-web-development',
            'description' => 'Learn the fundamentals of web development including HTML, CSS, and JavaScript. This comprehensive course will take you from beginner to proficient in building modern websites.',
            'short_description' => 'Master the basics of HTML, CSS, and JavaScript',
            'is_published' => true,
            'duration_hours' => 10,
            'difficulty' => 'beginner',
            'what_you_learn' => json_encode([
                'Build responsive websites from scratch',
                'Understand HTML structure and semantics',
                'Style websites with CSS and modern layouts',
                'Add interactivity with JavaScript',
            ]),
            'requirements' => json_encode([
                'A computer with internet access',
                'No prior programming experience required',
                'Enthusiasm to learn!',
            ]),
        ]);

        // Create modules with drip content
        $modules = [
            [
                'title' => 'Getting Started',
                'description' => 'Introduction to web development and setting up your environment',
                'order' => 1,
                'drip_days' => 0,
                'lessons' => [
                    ['title' => 'Welcome to the Course', 'duration_minutes' => 5, 'is_free_preview' => true],
                    ['title' => 'Setting Up Your Development Environment', 'duration_minutes' => 15],
                    ['title' => 'How the Web Works', 'duration_minutes' => 10],
                ],
            ],
            [
                'title' => 'HTML Fundamentals',
                'description' => 'Learn the building blocks of web pages',
                'order' => 2,
                'drip_days' => 3,
                'lessons' => [
                    ['title' => 'Introduction to HTML', 'duration_minutes' => 20],
                    ['title' => 'HTML Document Structure', 'duration_minutes' => 15],
                    ['title' => 'Working with Text and Links', 'duration_minutes' => 25],
                    ['title' => 'Images and Media', 'duration_minutes' => 20],
                    ['title' => 'Forms and Input Elements', 'duration_minutes' => 30],
                ],
            ],
            [
                'title' => 'CSS Styling',
                'description' => 'Make your websites beautiful with CSS',
                'order' => 3,
                'drip_days' => 7,
                'lessons' => [
                    ['title' => 'Introduction to CSS', 'duration_minutes' => 15],
                    ['title' => 'Selectors and Properties', 'duration_minutes' => 25],
                    ['title' => 'The Box Model', 'duration_minutes' => 20],
                    ['title' => 'Flexbox Layout', 'duration_minutes' => 30],
                    ['title' => 'CSS Grid', 'duration_minutes' => 30],
                    ['title' => 'Responsive Design', 'duration_minutes' => 25],
                ],
            ],
            [
                'title' => 'JavaScript Basics',
                'description' => 'Add interactivity to your websites',
                'order' => 4,
                'drip_days' => 14,
                'lessons' => [
                    ['title' => 'Introduction to JavaScript', 'duration_minutes' => 20],
                    ['title' => 'Variables and Data Types', 'duration_minutes' => 25],
                    ['title' => 'Functions and Scope', 'duration_minutes' => 30],
                    ['title' => 'DOM Manipulation', 'duration_minutes' => 35],
                    ['title' => 'Events and Interactivity', 'duration_minutes' => 30],
                    ['title' => 'Final Project', 'duration_minutes' => 60],
                ],
            ],
        ];

        foreach ($modules as $moduleData) {
            $lessons = $moduleData['lessons'];
            unset($moduleData['lessons']);
            
            $module = $course->modules()->create($moduleData);
            
            foreach ($lessons as $index => $lessonData) {
                $lessonData['order'] = $index + 1;
                $lessonData['content'] = '<p>This is the content for ' . $lessonData['title'] . '. In a real course, this would contain detailed explanations, code examples, and learning materials.</p>';
                $module->lessons()->create($lessonData);
            }
        }
    }
}











