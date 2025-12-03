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
        Schema::dropIfExists('book_reservations');
        
        Schema::create('book_reservations', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id');
            $table->unsignedBigInteger('book_id');
            $table->enum('status', ['pending', 'approved', 'rejected', 'picked_up', 'returned', 'cancelled'])->default('pending');
            $table->date('reservation_date');
            $table->date('pickup_date')->nullable();
            $table->date('due_date')->nullable();
            $table->date('return_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->foreign('user_id')->references('userId')->on('users')->onDelete('cascade');
            $table->foreign('book_id')->references('id')->on('books')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('book_reservations');
    }
};
