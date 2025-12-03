<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('userId'); // primary key
            $table->string('first_name', 100);
            $table->string('last_name', 100);
            $table->string('email', 100)->unique();
            $table->string('password', 255);
            $table->enum('role', ['student','faculty','headlibrarian','assistant','admin'])->default('student');
            $table->enum('account_status', ['pending','approved','rejected'])->default('pending');
            $table->timestamp('registration_date')->useCurrent();
            $table->rememberToken();
            $table->timestamps(); // created_at, updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
};
