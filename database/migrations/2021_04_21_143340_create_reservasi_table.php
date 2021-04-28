<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReservasiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reservasi', function (Blueprint $table) {
            $table->id('id_reservasi');
            $table->boolean('is_deleted');
            $table->date('tanggal_reservasi');
            $table->integer('sesi_reservasi');
            $table->string('id_customer');
            $table->integer('no_meja');
            $table->foreign('no_meja')
                  ->references('no_meja')
                  ->on('meja')
                  ->onDelete('cascade');
            $table->foreign('id_customer')
                  ->references('id_customer')
                  ->on('customer')
                  ->onDelete('cascade');
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
        Schema::dropIfExists('reservasi');
    }
}
