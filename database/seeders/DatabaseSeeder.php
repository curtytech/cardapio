<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Criar usuário de teste
        User::factory()->create([
            'name' => 'Restaurante Teste',
            'email' => 'test@example.com',
            'slug' => 'restaurante-teste',
            'role' => 'admin',
        ]);

        // Executar o seeder de restaurantes
        $this->call([
            RestaurantSeeder::class,
        ]);

        // Criar alguns usuários adicionais
        User::factory(10)->create();

        $this->command->info('Database seeding completed!');
        $this->command->info('Total users: ' . User::count());
        $this->command->info('Total categories: ' . Category::count());
        $this->command->info('Total products: ' . Product::count());
    }
}
