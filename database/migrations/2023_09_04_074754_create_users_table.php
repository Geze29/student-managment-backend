<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('email')->unique();
            $table->string('fname');
            $table->string('mname');
            $table->string('lname');
            $table->string('password');
            $table->date('birthDate');
            $table->enum('gender',['male','female']);
            $table->string('phoneNumber');
            $table->string('educationLevel');
            $table->string('address');
            $table->enum('role', ['student', 'admin','instructor','reception']);
            $table->string('imagePath');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
