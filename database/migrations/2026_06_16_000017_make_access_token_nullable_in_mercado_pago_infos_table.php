<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::connection()->getDriverName() === 'sqlite') {
            DB::statement('PRAGMA foreign_keys = OFF');
            DB::statement('DROP TABLE IF EXISTS "__temp__mercado_pago_infos"');

            DB::statement('
                CREATE TABLE "__temp__mercado_pago_infos" (
                    "id" integer primary key autoincrement not null,
                    "user_id" integer not null,
                    "mercadopago_access_token" varchar not null,
                    "mercadopago_public_key" varchar not null,
                    "access_token" varchar null,
                    "created_at" datetime null,
                    "updated_at" datetime null,
                    foreign key("user_id") references "users"("id") on delete cascade
                )
            ');

            DB::statement('
                INSERT INTO "__temp__mercado_pago_infos" (
                    "id", "user_id", "mercadopago_access_token", "mercadopago_public_key", "access_token", "created_at", "updated_at"
                )
                SELECT
                    "id", "user_id", "mercadopago_access_token", "mercadopago_public_key", "access_token", "created_at", "updated_at"
                FROM "mercado_pago_infos"
            ');

            DB::statement('DROP TABLE "mercado_pago_infos"');
            DB::statement('ALTER TABLE "__temp__mercado_pago_infos" RENAME TO "mercado_pago_infos"');
            DB::statement('PRAGMA foreign_keys = ON');

            return;
        }

        Schema::table('mercado_pago_infos', function (Blueprint $table) {
            $table->string('access_token')->nullable()->change();
        });
    }

    public function down(): void
    {
        if (DB::connection()->getDriverName() === 'sqlite') {
            return;
        }

        Schema::table('mercado_pago_infos', function (Blueprint $table) {
            $table->string('access_token')->nullable(false)->change();
        });
    }
};
