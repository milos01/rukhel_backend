<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTaskTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->increments('id');
            $table->string('subject')->unique();
            $table->string('slug')->unique();
            $table->integer('user_creator_id')->unsigned();
            $table->foreign('user_creator_id')->references('id')->on('users');
            $table->integer('user_solver_id')->nullable()->unsigned();
            $table->foreign('user_solver_id')->references('id')->on('users');
            $table->integer('category_id')->unsigned();
            $table->foreign('category_id')->references('id')->on('categories');
            $table->text('description');
            $table->text('solution_description')->nullable();
            $table->timestamp('biding_expires_at')->nullable();
            $table->string('status');
            $table->json('categories');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tasks');
    }
}
