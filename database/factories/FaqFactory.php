<?php

namespace Database\Factories;

use App\Models\Course;
use App\Models\Faq;
use Illuminate\Database\Eloquent\Factories\Factory;

class FaqFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Faq::class;

    public function definition()
    {
        return [
            'course_id' => Course::all()->random()->id,
            'question' => $this->faker->sentence . ' ?',
            'answer' =>$this->faker->sentences(5,true)
        ];
    }
}
