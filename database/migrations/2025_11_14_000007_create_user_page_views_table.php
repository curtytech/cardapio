<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('user_page_views', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('slug')->index();
            $table->unsignedBigInteger('views_count')->default(0);
            $table->timestamp('last_viewed_at')->nullable();
            $table->timestamps();

            $table->unique('user_id'); // um registro por usuário
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_page_views');
    }
};