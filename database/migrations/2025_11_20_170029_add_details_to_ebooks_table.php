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
        if (Schema::hasColumn('ebooks', 'title')) {
            return;
        }

        Schema::table('ebooks', function (Blueprint $table) {
            $table->string('title')->after('id');
            $table->string('author')->after('title');
            $table->string('category')->default('education')->after('author');
            $table->string('file_path')->nullable()->after('category');
            $table->string('cover_path')->nullable()->after('file_path');
            $table->unsignedInteger('views')->default(0)->after('cover_path');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (!Schema::hasColumn('ebooks', 'title')) {
            return;
        }

        Schema::table('ebooks', function (Blueprint $table) {
            $dropColumns = collect([
                'title',
                'author',
                'category',
                'file_path',
                'cover_path',
                'views',
            ])->filter(fn ($column) => Schema::hasColumn('ebooks', $column))->all();

            if (!empty($dropColumns)) {
                $table->dropColumn($dropColumns);
            }
        });
    }
};
