<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Course;
use App\Models\CourseResource;
use App\Models\Enrollment;
use App\Models\Faq;
use App\Models\Level;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{

    public function run()
    {
        User::factory(10)->create();
        Level::factory(10)->create();
        Course::factory(120)->create();
        Enrollment::factory(400)->create();
        CourseResource::factory(300)->create();
        Comment::factory(300)->create();
        Faq::factory(400)->create();
    }
}
