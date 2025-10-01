<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class RestaurantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Criar 5 restaurantes
        $restaurants = User::factory(5)->restaurant()->create();

        foreach ($restaurants as $restaurant) {
            // Criar categorias para cada restaurante
            $categories = [
                ['name' => 'Pizzas', 'description' => 'Deliciosas pizzas artesanais', 'color' => '#FF6B6B'],
                ['name' => 'Hambúrgueres', 'description' => 'Hambúrgueres suculentos e saborosos', 'color' => '#4ECDC4'],
                ['name' => 'Bebidas', 'description' => 'Refrigerantes, sucos e bebidas geladas', 'color' => '#45B7D1'],
                ['name' => 'Sobremesas', 'description' => 'Doces e sobremesas irresistíveis', 'color' => '#F7DC6F'],
                ['name' => 'Pratos Principais', 'description' => 'Refeições completas e nutritivas', 'color' => '#BB8FCE'],
            ];

            $createdCategories = [];
            foreach ($categories as $categoryData) {
                $category = Category::create([
                    'user_id' => $restaurant->id,
                    'name' => $categoryData['name'],
                    'slug' => \Str::slug($categoryData['name']) . '-' . $restaurant->id,
                    'description' => $categoryData['description'],
                    'color' => $categoryData['color'],
                    'is_active' => true,
                ]);
                $createdCategories[] = $category;
            }

            // Criar produtos para cada categoria
            foreach ($createdCategories as $category) {
                $productCount = rand(3, 8); // Entre 3 e 8 produtos por categoria
                
                for ($i = 0; $i < $productCount; $i++) {
                    Product::factory()->create([
                        'user_id' => $restaurant->id,
                        'category_id' => $category->id,
                    ]);
                }
            }
        }

        $this->command->info('Criados ' . count($restaurants) . ' restaurantes com categorias e produtos!');
    }
}