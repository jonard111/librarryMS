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
        if (Schema::hasColumn('books', 'title')) {
            return;
        }

        Schema::table('books', function (Blueprint $table) {
            $table->string('title')->after('id');
            $table->string('author')->after('title');
            $table->string('isbn')->nullable()->after('author');
            $table->string('publisher')->nullable()->after('isbn');
            $table->string('category')->default('education')->after('publisher');
            $table->unsignedInteger('copies')->default(1)->after('category');
            $table->string('cover_path')->nullable()->after('copies');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (!Schema::hasColumn('books', 'title')) {
            return;
        }

        Schema::table('books', function (Blueprint $table) {
            $dropColumns = collect([
                'title',
                'author',
                'isbn',
                'publisher',
                'category',
                'copies',
                'cover_path',
            ])->filter(fn ($column) => Schema::hasColumn('books', $column))->all();

            if (!empty($dropColumns)) {
                $table->dropColumn($dropColumns);
            }
        });
    }
};
