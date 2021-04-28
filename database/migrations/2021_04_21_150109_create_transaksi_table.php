<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransaksiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaksi', function (Blueprint $table) {
            $table->id('id_transaksi');
            $table->date('tanggal_transaksi');
            $table->integer('sub_total');
            $table->integer('tax');
            $table->integer('service');
            $table->integer('grand_total');
            $table->string('metode_pembayaran');
            $table->string('verifikasi_edc');
            $table->string('id_karyawan');
            $table->string('id_pesanan');
            $table->string('id_kartu');
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
        Schema::dropIfExists('transaksi');
    }
}
