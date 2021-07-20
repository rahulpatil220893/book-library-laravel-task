<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRentBookTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rent_book', function (Blueprint $table) {
            $table->id('id');
            $table->integer('book_id');
            $table->integer('user_id');
            $table->timestamps();

            $table->foreign('book_id')->references('b_id')->on('books')->onDelete('cascade');
            $table->foreign('user_id')->references('u_id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rent_book', function(Blueprint $table){
            $table->dropForeign('book_id');
            $table->dropForeign('user_id'); 
        });
        Schema::dropIfExists('rent_book');
    }
}
