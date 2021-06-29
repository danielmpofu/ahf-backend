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

//        User::factory(8)->create();
        Level::factory(4)->create();
//        Course::factory(40)->create();
//        Enrollment::factory(100)->create();
//        CourseResource::factory(300)->create();
//        Comment::factory(600)->create();
//        Faq::factory(100)->create();
    }
}
