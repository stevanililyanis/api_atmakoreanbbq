<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenuTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menu', function (Blueprint $table) {
            $table->id('id_menu');
            $table->string('nama_menu');
            $table->string('deskripsi_menu');
            $table->string('gambar');
            $table->integer('harga_menu');
            $table->integer('serving_size');
            $table->string('unit');
            $table->boolean('ketersediaan');
            $table->string('id_bahan');
            $table->foreign('id_bahan')
                ->references('id_bahan')
                ->on('bahan')
                ->onDelete('cascade');
            $table->boolean('is_deleted');
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
        Schema::dropIfExists('menu');
    }
}
