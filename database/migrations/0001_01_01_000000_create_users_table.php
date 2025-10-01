<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('image_logo')->nullable();
            $table->string('image_banner')->nullable();
            $table->string('slug')->unique();      
            $table->string('celphone')->unique()->nullable();
            $table->string('zipcode')->nullable();
            $table->string('address')->nullable();
            $table->string('neighborhood')->nullable();
            $table->string('city')->nullable();
            $table->string('number')->nullable();
            $table->string('state')->nullable();
            $table->string('complement')->nullable();
            $table->string('instagram')->nullable();
            $table->string('facebook')->nullable();
            $table->string('whatsapp')->nullable();
            $table->string('email')->unique();
            $table->string('color_primary')->default('#0000FF');
            $table->string('color_secondary')->default('#000000');

            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('role')->default('admin');
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });

        // Criar usuários administrativos após criar as tabelas
        User::firstOrCreate(
            ['email' => 'admin@admin'],
            [
                'name' => 'Admin Principal',
                'slug' => 'admin-principal',
                'email_verified_at' => now(),
                'password' => Hash::make('12345678'),
                'role' => 'admin',
                'celphone' => '(11) 99999-9999',
                'address' => 'Rua Principal, 123 - Centro',
                'zipcode' => '01234-567',
            ]
        );
        
        User::firstOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'Admin Secundário',
                'slug' => 'admin-secundario',
                'email_verified_at' => now(),
                'password' => Hash::make('12345678'),
                'role' => 'admin',
                'celphone' => '(11) 88888-8888',
                'address' => 'Avenida Central, 456 - Centro',
                'zipcode' => '01234-568',
            ]
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
