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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_number');
            $table->unsignedBigInteger('user_id')->nullable(); // jika belum login
            $table->integer('total_price');
            $table->integer('total_item');
            $table->integer('cash');
            $table->integer('change');
            $table->date('date');
            $table->timestamps();

            // Opsional jika ada relasi user
            // $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
};
