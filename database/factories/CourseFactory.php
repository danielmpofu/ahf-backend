<?php

namespace Database\Factories;

use App\Models\Course;
use App\Models\Level;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CourseFactory extends Factory
{

    protected $model = Course::class;

    public function definition()
    {
        return [
            'title' => $this->faker->words(3, true),
            'description' => $this->faker->sentences(10, true),
            'entry_requirements' => 'For All',
            'optional' => $this->faker->randomElement(['Compulsory', 'Optional', 'Core Course']),
            'cover_image' => 'images/img_' . $this->faker->numberBetween(1, 25) . '.jpg',
            'duration' => $this->faker->numberBetween(1, 25) . ' ' . $this->faker->randomElement(['Days', 'Months', 'Weeks', 'Hours']),
            'level' => Level::all()->random()->id,
            'instructor_id' => User::all()->random()->id,
        ];
    }
}
