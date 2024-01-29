<?php

namespace Database\Factories;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Customer>
 */
class CustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name'  => $this->faker->name,
            'email' => $this->faker->unique()->email,
            'phone' => $this->faker->phoneNumber,

            'linkedin'  => 'https://www.linkedin.com/in/' . $this->faker->userName,
            'facebook'  => 'https://www.facebook.com/' . $this->faker->userName,
            'x-twitter' => 'https://twitter.com/' . $this->faker->userName,

            'address' => $this->faker->address,
            'city'    => $this->faker->city,
            'state'   => $this->faker->state,
            'country' => $this->faker->country,
            'zip'     => $this->faker->postcode,

            'age'    => $this->faker->numberBetween(18, 80),
            'gender' => $this->faker->randomElement(['male', 'female', 'other']),
        ];
    }
}
