<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            // Define other attributes as needed
            'password' => bcrypt('password'), // Example password, you can change this
            'email_verified_at' => now(), // For testing purposes, you can mark all generated users as verified
        ];
    }
}