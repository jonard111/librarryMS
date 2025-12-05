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
            if (!Schema::hasColumn('book_reservations', 'loan_duration')) {
                $table->unsignedSmallInteger('loan_duration')->default(7)->after('reservation_date');
            }

            if (!Schema::hasColumn('book_reservations', 'loan_duration_unit')) {
                $table->enum('loan_duration_unit', ['day', 'hour'])->default('day')->after('loan_duration');
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
            if (Schema::hasColumn('book_reservations', 'loan_duration_unit')) {
                $table->dropColumn('loan_duration_unit');
            }

            if (Schema::hasColumn('book_reservations', 'loan_duration')) {
                $table->dropColumn('loan_duration');
            }
        });
    }
};



