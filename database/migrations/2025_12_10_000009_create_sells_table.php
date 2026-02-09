<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sells', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('table_id')->constrained('restaurant_tables')->onDelete('cascade');
            $table->ipAddress('ip')->default('0.0.0.0');
            $table->string('client_name')->nullable();
            $table->timestamp('date')->nullable();
            $table->string('observation')->nullable();
            $table->boolean('is_paid')->default(false);
            $table->boolean('is_finished')->default(false);
            $table->string('status')->default('Preparando');
            
            $table->decimal('total', 10, 2)->default(0)->after('quantity');
            $table->timestamps();
        });

        Schema::create('sell_products_groups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sell_id')->constrained('sells')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->integer('quantity')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sells');
        Schema::dropIfExists('sell_products_groups');
    }
};
