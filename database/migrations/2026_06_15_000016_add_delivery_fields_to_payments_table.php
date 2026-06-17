<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::getConnection()->getDriverName() === 'sqlite') {
            $this->dropMonthlySalesView();

            if (! Schema::hasTable('payments') && Schema::hasTable('__temp__payments')) {
                Schema::rename('__temp__payments', 'payments');
            }
        }

        Schema::table('payments', function (Blueprint $table) {
            if (! Schema::hasColumn('payments', 'payment_context')) {
                $table->string('payment_context')->nullable()->after('user_id');
            }

            if (! Schema::hasColumn('payments', 'sell_id')) {
                $table->foreignId('sell_id')->nullable()->after('payment_context')->constrained('sells')->nullOnDelete();
            }

            if (! Schema::hasColumn('payments', 'delivery_id')) {
                $table->foreignId('delivery_id')->nullable()->after('sell_id')->constrained('deliveries')->nullOnDelete();
            }

            if (! Schema::hasColumn('payments', 'amount')) {
                $table->decimal('amount', 10, 2)->nullable()->after('expiration_date');
            }
        });

        if (Schema::getConnection()->getDriverName() === 'sqlite') {
            $this->createMonthlySalesView();
        }
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropConstrainedForeignId('delivery_id');
            $table->dropConstrainedForeignId('sell_id');
            $table->dropColumn([
                'payment_context',
                'amount',
            ]);
        });
    }

    private function dropMonthlySalesView(): void
    {
        DB::statement('DROP VIEW IF EXISTS monthly_sales_view');
    }

    private function createMonthlySalesView(): void
    {
        DB::statement("
            CREATE VIEW monthly_sales_view AS
            SELECT
                s.user_id || '-' || strftime('%Y-%m', s.date) as id,
                s.user_id,
                strftime('%Y-%m', s.date) as month_year,
                COALESCE(SUM(spg.quantity * p.sell_price), 0) as total,
                COUNT(DISTINCT s.id) as quantity
            FROM sells s
            LEFT JOIN sell_products_groups spg ON s.id = spg.sell_id
            LEFT JOIN products p ON spg.product_id = p.id
            GROUP BY s.user_id, strftime('%Y-%m', s.date)
        ");
    }
};
