<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->company();
        
        return [
            'name' => $name,
            'slug' => Str::slug($name) . '-' . fake()->unique()->numberBetween(1000, 9999),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'celphone' => fake()->phoneNumber(),
            'zipcode' => fake()->postcode(),
            'address' => fake()->streetAddress() . ', ' . fake()->city(),
            'role' => fake()->randomElement(['admin', 'manager', 'user']),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    /**
     * Create a restaurant owner user.
     */
    public function restaurant(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => fake()->randomElement([
                'Pizzaria Bella Vista',
                'Restaurante Sabor Caseiro',
                'Lanchonete do João',
                'Café Central',
                'Hamburgueria Premium',
                'Sushi House',
                'Cantina Italiana',
                'Churrascaria Gaúcha',
                'Padaria Pão Quente',
                'Sorveteria Gelato'
            ]),
            'role' => 'admin',
        ]);
    }
}
