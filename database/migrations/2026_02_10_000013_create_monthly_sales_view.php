<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $driver = DB::connection()->getDriverName();

        // Compatibility for SQLite (local) and MySQL (production)
        $monthExpr = $driver === 'sqlite' ? "strftime('%Y-%m', s.date)" : "DATE_FORMAT(s.date, '%Y-%m')";

        $idExpr = $driver === 'sqlite'
            ? "s.user_id || '-' || strftime('%Y-%m', s.date)"
            : "CONCAT(s.user_id, '-', DATE_FORMAT(s.date, '%Y-%m'))";

        DB::statement("DROP VIEW IF EXISTS monthly_sales_view");

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
    public function down(): void
    {
        DB::statement("DROP VIEW IF EXISTS monthly_sales_view");
    }
};
