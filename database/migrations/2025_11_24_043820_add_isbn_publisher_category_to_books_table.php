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
        Schema::table('books', function (Blueprint $table) {
            if (!Schema::hasColumn('books', 'isbn')) {
                $table->string('isbn')->nullable()->after('author');
            }
            if (!Schema::hasColumn('books', 'publisher')) {
                $table->string('publisher')->nullable()->after('isbn');
            }
            if (!Schema::hasColumn('books', 'category')) {
                $table->string('category')->default('education')->after('publisher');
            }
            if (!Schema::hasColumn('books', 'copies')) {
                $table->unsignedInteger('copies')->default(1)->after('category');
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
        Schema::table('books', function (Blueprint $table) {
            $columnsToDrop = [];
            
            if (Schema::hasColumn('books', 'isbn')) {
                $columnsToDrop[] = 'isbn';
            }
            if (Schema::hasColumn('books', 'publisher')) {
                $columnsToDrop[] = 'publisher';
            }
            if (Schema::hasColumn('books', 'category')) {
                $columnsToDrop[] = 'category';
            }
            if (Schema::hasColumn('books', 'copies')) {
                $columnsToDrop[] = 'copies';
            }
            
            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }
};
