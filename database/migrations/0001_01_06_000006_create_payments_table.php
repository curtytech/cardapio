<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('mercadopago_payment_id')->nullable();
            $table->string('mercadopago_preference_id')->nullable();
            $table->string('mercadopago_status')->nullable();
            $table->dateTime('data_pagamento')->nullable();
            $table->text('mercadopago_response')->nullable();
            $table->dateTime('expiration_date')->nullable();
            $table->timestamps();
        });

        if (Schema::getConnection()->getDriverName() === 'mysql') {
            DB::statement(
                'ALTER TABLE payments MODIFY expiration_date DATETIME GENERATED ALWAYS AS (DATE_ADD(data_pagamento, INTERVAL 1 YEAR)) STORED'
            );
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};