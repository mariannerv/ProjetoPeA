<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'gender' => $this->faker->randomElement(['male', 'female']),
            'birthdate' => $this->faker->date(),
            'address' => $this->faker->address,
            'codigo_postal' => $this->faker->postcode,
            'localidade' => $this->faker->city,
            'civilId' => $this->faker->numerify('#########'),
            'taxId' => $this->faker->numerify('#########'),
            'contactNumber' => $this->faker->phoneNumber,
            'email' => $this->faker->unique()->safeEmail,
            'password' => bcrypt('password'),
            'account_status' => 'active', 
            'token' => $this->faker->md5, 
            'email_verified_at' => $this->faker->dateTimeBetween('-1 year', 'now'), // Random date for email verification
            'bid_history' => [], 
            'lost_objects' => [], 
        ];
    }
}
