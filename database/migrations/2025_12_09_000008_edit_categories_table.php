<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Remove índice único do slug (nome é inferido)
        Schema::table('categories', function (Blueprint $table) {
            $table->dropUnique(['slug']);
        });

        // Apaga a coluna slug
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('slug');
        });

        // Recria a coluna slug permitindo NULL
        Schema::table('categories', function (Blueprint $table) {
            $table->string('slug')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove a coluna recriada
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('slug');
        });

        // Recria slug como NOT NULL + índice único
        Schema::table('categories', function (Blueprint $table) {
            $table->string('slug')->unique();
        });
    }
};