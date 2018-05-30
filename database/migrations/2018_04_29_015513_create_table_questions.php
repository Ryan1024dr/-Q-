<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableQuestions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /*创建question表*/
        Schema::create('question', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title',64);
            $table->text('desc')->nullable()->comment('desction');
            $table->unsignedInteger('user_id');
            $table->string('status')->default('ok');
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users'); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('question');
    }
}
