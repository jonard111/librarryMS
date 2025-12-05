<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('book_reservations', function (Blueprint $table) {
            if (!Schema::hasColumn('book_reservations', 'fine_amount')) {
                $table->decimal('fine_amount', 8, 2)->default(0)->after('notes');
            }

            if (!Schema::hasColumn('book_reservations', 'fine_paid_at')) {
                $table->timestamp('fine_paid_at')->nullable()->after('fine_amount');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('book_reservations', function (Blueprint $table) {
            if (Schema::hasColumn('book_reservations', 'fine_paid_at')) {
                $table->dropColumn('fine_paid_at');
            }

            if (Schema::hasColumn('book_reservations', 'fine_amount')) {
                $table->dropColumn('fine_amount');
            }
        });
    }
};



