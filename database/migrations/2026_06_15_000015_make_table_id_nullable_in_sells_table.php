<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $driver = DB::connection()->getDriverName();

        $this->dropMonthlySalesView();

        if ($driver === 'sqlite') {
            if (! Schema::hasTable('sells') && Schema::hasTable('__temp__sells')) {
                DB::statement('ALTER TABLE "__temp__sells" RENAME TO "sells"');
                $this->createMonthlySalesView($driver);

                return;
            }

            $this->rebuildSellsTableForSqlite(nullable: true);

            $this->createMonthlySalesView($driver);

            return;
        }

        Schema::table('sells', function (Blueprint $table) {
            $table->dropConstrainedForeignId('table_id');
        });

        Schema::table('sells', function (Blueprint $table) {
            $table->foreignId('table_id')->nullable()->constrained('restaurant_tables')->nullOnDelete();
        });

        $this->createMonthlySalesView($driver);
    }

    public function down(): void
    {
        $driver = DB::connection()->getDriverName();

        $this->dropMonthlySalesView();

        if ($driver === 'sqlite') {
            return;

        }

        Schema::table('sells', function (Blueprint $table) {
            $table->dropConstrainedForeignId('table_id');
        });

        Schema::table('sells', function (Blueprint $table) {
            $table->foreignId('table_id')->constrained('restaurant_tables')->onDelete('cascade');
        });

        $this->createMonthlySalesView($driver);
    }

    private function dropMonthlySalesView(): void
    {
        DB::statement('DROP VIEW IF EXISTS monthly_sales_view');
    }

    private function createMonthlySalesView(string $driver): void
    {
        $monthExpr = $driver === 'sqlite' ? "strftime('%Y-%m', s.date)" : "DATE_FORMAT(s.date, '%Y-%m')";

        $idExpr = $driver === 'sqlite'
            ? "s.user_id || '-' || strftime('%Y-%m', s.date)"
            : "CONCAT(s.user_id, '-', DATE_FORMAT(s.date, '%Y-%m'))";

        DB::statement("
            CREATE VIEW monthly_sales_view AS
            SELECT
                $idExpr as id,
                s.user_id,
                $monthExpr as month_year,
                COALESCE(SUM(spg.quantity * p.sell_price), 0) as total,
                COUNT(DISTINCT s.id) as quantity
            FROM sells s
            LEFT JOIN sell_products_groups spg ON s.id = spg.sell_id
            LEFT JOIN products p ON spg.product_id = p.id
            GROUP BY s.user_id, $monthExpr
        ");
    }

    private function rebuildSellsTableForSqlite(bool $nullable): void
    {
        DB::statement('PRAGMA foreign_keys = OFF');
        DB::statement('DROP TABLE IF EXISTS "__temp__sells"');

        $tableIdColumn = $nullable
            ? '"table_id" integer null'
            : '"table_id" integer not null';

        DB::statement("
            CREATE TABLE \"__temp__sells\" (
                \"id\" integer primary key autoincrement not null,
                \"user_id\" integer not null,
                $tableIdColumn,
                \"ip\" varchar not null default '0.0.0.0',
                \"client_name\" varchar null,
                \"date\" datetime null,
                \"observation\" varchar null,
                \"is_paid\" tinyint(1) not null default '0',
                \"is_finished\" tinyint(1) not null default '0',
                \"status\" varchar not null default 'Preparando',
                \"total\" numeric not null default '0',
                \"created_at\" datetime null,
                \"updated_at\" datetime null,
                foreign key(\"user_id\") references \"users\"(\"id\") on delete cascade,
                foreign key(\"table_id\") references \"restaurant_tables\"(\"id\") on delete set null
            )
        ");

        DB::statement('
            INSERT INTO "__temp__sells" (
                "id", "user_id", "table_id", "ip", "client_name", "date", "observation",
                "is_paid", "is_finished", "status", "total", "created_at", "updated_at"
            )
            SELECT
                "id", "user_id", "table_id", "ip", "client_name", "date", "observation",
                "is_paid", "is_finished", "status", "total", "created_at", "updated_at"
            FROM "sells"
        ');

        DB::statement('DROP TABLE "sells"');
        DB::statement('ALTER TABLE "__temp__sells" RENAME TO "sells"');
        DB::statement('PRAGMA foreign_keys = ON');
    }
};
