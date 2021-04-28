<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetilPesananTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detil_pesanan', function (Blueprint $table) {
            $table->id('id_detil_pesanan');
            $table->integer('jumlah_pesanan');
            $table->integer('subtotal_harga');
            $table->string('status_pesanan');
            $table->string('id_menu');
            $table->string('id_pesanan');
            $table->foreign('id_menu')
                ->references('id_menu')
                ->on('menu');
            $table->foreign('id_pesanan')
                ->references('id_pesanan')
                ->on('pesanan');
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
        Schema::dropIfExists('detil_pesanan');
    }
}
