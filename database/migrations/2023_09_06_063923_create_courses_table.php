<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('courseCode')->unique();
            $table->string('courseName');
            $table->unsignedBigInteger('instructor_id')->nullable();
            $table->foreign('instructor_id')
                    ->references('id')
                    ->on('instructors');
            $table->integer('maxCapacity')->nullable();
            $table->integer('enrollmentNumber')->nullable()->default(0);
            $table->decimal('fee')->nullable();
            $table->enum('status',['active','closed','completed'])->default('active');
            $table->date('classStartDate')->nullable();
            $table->date('classEndDate')->nullable();
            $table->enum('enrollmentType',['summer','regular']);
            $table->string('dayTaken')->nullable();
            $table->string('backgroundURL')->nullable();
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
        Schema::dropIfExists('courses');
    }
}
