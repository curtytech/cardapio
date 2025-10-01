<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $products = [
            // Pizzas
            ['name' => 'Pizza Margherita', 'description' => 'Molho de tomate, mussarela, manjericão fresco e azeite', 'price' => 35.90],
            ['name' => 'Pizza Calabresa', 'description' => 'Molho de tomate, mussarela, calabresa e cebola', 'price' => 38.90],
            ['name' => 'Pizza Portuguesa', 'description' => 'Molho de tomate, mussarela, presunto, ovos, cebola e azeitona', 'price' => 42.90],
            ['name' => 'Pizza Quatro Queijos', 'description' => 'Molho de tomate, mussarela, gorgonzola, parmesão e provolone', 'price' => 45.90],
            
            // Hambúrgueres
            ['name' => 'X-Burger Clássico', 'description' => 'Hambúrguer bovino, queijo, alface, tomate e molho especial', 'price' => 25.90],
            ['name' => 'X-Bacon', 'description' => 'Hambúrguer bovino, bacon, queijo, alface, tomate e molho barbecue', 'price' => 28.90],
            ['name' => 'X-Frango', 'description' => 'Filé de frango grelhado, queijo, alface, tomate e maionese', 'price' => 24.90],
            ['name' => 'X-Vegano', 'description' => 'Hambúrguer de grão-de-bico, queijo vegano, alface, tomate e molho tahine', 'price' => 26.90],
            
            // Bebidas
            ['name' => 'Coca-Cola 350ml', 'description' => 'Refrigerante de cola gelado', 'price' => 5.50],
            ['name' => 'Suco de Laranja Natural', 'description' => 'Suco natural de laranja 300ml', 'price' => 8.90],
            ['name' => 'Água Mineral 500ml', 'description' => 'Água mineral sem gás', 'price' => 3.50],
            ['name' => 'Cerveja Artesanal IPA', 'description' => 'Cerveja artesanal India Pale Ale 355ml', 'price' => 12.90],
            
            // Sobremesas
            ['name' => 'Pudim de Leite', 'description' => 'Pudim cremoso de leite condensado com calda de caramelo', 'price' => 8.90],
            ['name' => 'Brownie com Sorvete', 'description' => 'Brownie de chocolate quente com sorvete de baunilha', 'price' => 12.90],
            ['name' => 'Açaí na Tigela', 'description' => 'Açaí com granola, banana e mel', 'price' => 15.90],
            
            // Pratos Principais
            ['name' => 'Filé à Parmegiana', 'description' => 'Filé bovino empanado com molho de tomate e queijo, acompanha arroz e batata frita', 'price' => 32.90],
            ['name' => 'Salmão Grelhado', 'description' => 'Salmão grelhado com legumes no vapor e arroz integral', 'price' => 38.90],
            ['name' => 'Feijoada Completa', 'description' => 'Feijoada tradicional com acompanhamentos', 'price' => 28.90],
        ];

        $product = fake()->randomElement($products);

        return [
            'user_id' => User::factory(),
            'category_id' => Category::factory(),
            'barcode' => fake()->unique()->numberBetween(1000000000, 9999999999),
            'name' => $product['name'],
            'description' => $product['description'],
            'sell_price' => $product['price'],
            'status' => fake()->randomElement(['active', 'inactive', 'out_of_stock']),
            'features' => $this->generateFeatures(),
        ];
    }

    /**
     * Generate random features for the product.
     */
    private function generateFeatures(): array
    {
        $allFeatures = [
            'Vegetariano',
            'Vegano',
            'Sem Glúten',
            'Sem Lactose',
            'Picante',
            'Orgânico',
            'Artesanal',
            'Promocional',
            'Novo',
            'Mais Vendido',
            'Chef Especial',
            'Low Carb',
            'Fitness',
            'Tradicional'
        ];

        $numFeatures = fake()->numberBetween(0, 4);
        return fake()->randomElements($allFeatures, $numFeatures);
    }

    /**
     * Indicate that the product should be active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'active',
        ]);
    }

    /**
     * Indicate that the product should be inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'inactive',
        ]);
    }

    /**
     * Indicate that the product should be out of stock.
     */
    public function outOfStock(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'out_of_stock',
        ]);
    }

    /**
     * Create a pizza product.
     */
    public function pizza(): static
    {
        $pizzas = [
            ['name' => 'Pizza Margherita', 'description' => 'Molho de tomate, mussarela, manjericão fresco e azeite', 'price' => 35.90],
            ['name' => 'Pizza Calabresa', 'description' => 'Molho de tomate, mussarela, calabresa e cebola', 'price' => 38.90],
            ['name' => 'Pizza Portuguesa', 'description' => 'Molho de tomate, mussarela, presunto, ovos, cebola e azeitona', 'price' => 42.90],
            ['name' => 'Pizza Quatro Queijos', 'description' => 'Molho de tomate, mussarela, gorgonzola, parmesão e provolone', 'price' => 45.90],
            ['name' => 'Pizza Pepperoni', 'description' => 'Molho de tomate, mussarela e pepperoni', 'price' => 40.90],
        ];

        $pizza = fake()->randomElement($pizzas);

        return $this->state(fn (array $attributes) => [
            'name' => $pizza['name'],
            'description' => $pizza['description'],
            'sell_price' => $pizza['price'],
        ]);
    }

    /**
     * Create a burger product.
     */
    public function burger(): static
    {
        $burgers = [
            ['name' => 'X-Burger Clássico', 'description' => 'Hambúrguer bovino, queijo, alface, tomate e molho especial', 'price' => 25.90],
            ['name' => 'X-Bacon', 'description' => 'Hambúrguer bovino, bacon, queijo, alface, tomate e molho barbecue', 'price' => 28.90],
            ['name' => 'X-Frango', 'description' => 'Filé de frango grelhado, queijo, alface, tomate e maionese', 'price' => 24.90],
            ['name' => 'X-Vegano', 'description' => 'Hambúrguer de grão-de-bico, queijo vegano, alface, tomate e molho tahine', 'price' => 26.90],
        ];

        $burger = fake()->randomElement($burgers);

        return $this->state(fn (array $attributes) => [
            'name' => $burger['name'],
            'description' => $burger['description'],
            'sell_price' => $burger['price'],
        ]);
    }
}