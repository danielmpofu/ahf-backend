<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'username' => $this->faker->userName,
            'phone'=>$this->faker->phoneNumber,
            'user_type'=>$this->faker->randomElement(['instructor','student']),
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'address' => $this->faker->address,
            'dob' => $this->faker->dateTime,
            'country' => $this->faker->country,
            'bio' => $this->faker->sentences(5,true),
            'email' => $this->faker->unique()->safeEmail,
            'email_verified_at' => now(),
            'title'=>$this->faker->randomElement(['Mr','Miss','Mrs','Sir','Prof','Doc']),
            'gender'=>$this->faker->randomElement(['Male','Female']),
            'password' => bcrypt('Password@1'),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }
}
