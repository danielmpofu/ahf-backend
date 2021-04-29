<?php

namespace Database\Factories;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommentFactory extends Factory
{
    protected $model = Comment::class;

    public function definition()
    {
        return [
            'message'=>$this->faker->sentences(2,true),
            'user_id'=>User::all()->random()->id,
            'entity_id'=>$this->faker->numberBetween(1,120),
            'entity_type'=>$this->faker->randomElement(['video','course','document','mcq']),
        ];
    }
}
