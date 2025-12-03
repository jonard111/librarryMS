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
        Schema::table('ebooks', function (Blueprint $table) {
            if (!Schema::hasColumn('ebooks', 'category')) {
                $table->string('category')->default('education')->after('author');
            }
            if (!Schema::hasColumn('ebooks', 'file_path')) {
                $table->string('file_path')->nullable()->after('category');
            }
            if (!Schema::hasColumn('ebooks', 'views')) {
                $table->unsignedInteger('views')->default(0)->after('cover_path');
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
        Schema::table('ebooks', function (Blueprint $table) {
            $columnsToDrop = [];
            
            if (Schema::hasColumn('ebooks', 'category')) {
                $columnsToDrop[] = 'category';
            }
            if (Schema::hasColumn('ebooks', 'file_path')) {
                $columnsToDrop[] = 'file_path';
            }
            if (Schema::hasColumn('ebooks', 'views')) {
                $columnsToDrop[] = 'views';
            }
            
            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }
};
