<?php

namespace Database\Factories;

use App\Models\Course;
use App\Models\CourseResource;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CourseResourceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CourseResource::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'course_id' => Course::all()->random()->id,
            'title' => $this->faker->words(5, true),
            'description' => $this->faker->sentences(5, true),
            'file_type' => 'Mp4 Video',
            'file_extension' => '.mp4',
            'created_by'=>User::all()->random()->id,
            'path' => 'video_tutorial_' . $this->faker->numberBetween(1, 40) . '.mp4',
            'file_size' => $this->faker->numberBetween(10000, 25000),
            'study_length' => $this->faker->numberBetween(1, 24).$this->faker->randomElement(['Hours','Minutes']),
        ];
    }
}
