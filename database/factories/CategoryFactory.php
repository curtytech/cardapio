<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $categories = [
            ['name' => 'Pizzas', 'description' => 'Deliciosas pizzas artesanais', 'color' => '#FF6B6B'],
            ['name' => 'Hambúrgueres', 'description' => 'Hambúrgueres suculentos e saborosos', 'color' => '#4ECDC4'],
            ['name' => 'Bebidas', 'description' => 'Refrigerantes, sucos e bebidas geladas', 'color' => '#45B7D1'],
            ['name' => 'Sobremesas', 'description' => 'Doces e sobremesas irresistíveis', 'color' => '#F7DC6F'],
            ['name' => 'Pratos Principais', 'description' => 'Refeições completas e nutritivas', 'color' => '#BB8FCE'],
            ['name' => 'Entradas', 'description' => 'Aperitivos e petiscos', 'color' => '#85C1E9'],
            ['name' => 'Saladas', 'description' => 'Saladas frescas e saudáveis', 'color' => '#82E0AA'],
            ['name' => 'Massas', 'description' => 'Massas tradicionais e especiais', 'color' => '#F8C471'],
            ['name' => 'Carnes', 'description' => 'Carnes grelhadas e assadas', 'color' => '#CD6155'],
            ['name' => 'Peixes', 'description' => 'Peixes frescos e frutos do mar', 'color' => '#5DADE2'],
        ];

        $category = fake()->randomElement($categories);
        $name = $category['name'];

        return [
            'user_id' => User::factory(),
            'name' => $name,
            'slug' => Str::slug($name) . '-' . fake()->unique()->numberBetween(100, 999),
            'description' => $category['description'],
            'color' => $category['color'],
            'is_active' => fake()->boolean(90), // 90% chance of being active
        ];
    }

    /**
     * Indicate that the category should be active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => true,
        ]);
    }

    /**
     * Indicate that the category should be inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }
}