<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => $this->faker->firstName,
            'paternal_name' => $this->faker->lastName,
            'maternal_name' => $this->faker->lastName,
            'email' => 'admin@example.com',
            'code' => 'admin',
            'birthday' => $this->faker->date('11.12.10'),
            'address' => $this->faker->streetAddress,
            'phone' => $this->faker->e164PhoneNumber,
            'contact_name' => $this->faker->name,
            'comments' => $this->faker->realTextBetween(50),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return static
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
