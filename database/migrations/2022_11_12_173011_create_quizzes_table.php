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
        Schema::create('quizzes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('category');
            $table->string('description');
            $table->boolean('timer')->default(0);
            $table->integer('timer_per_question')->default(null);
            $table->integer('number_of_questions');
            $table->integer('needed_to_success');
            $table->text('questions');
            $table->text('answers');
            $table->integer('successful');
            $table->integer('fails');
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
        Schema::dropIfExists('quizzes');
    }
};
