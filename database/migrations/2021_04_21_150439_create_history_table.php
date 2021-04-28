<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('history', function (Blueprint $table) {
            $table->id('id_history');
            $table->string('tipe_stock');
            $table->integer('stock');
            $table->date('tanggal');
            $table->integer('harga_bahan');
            $table->boolean('is_deleted');
            $table->string('id_bahan');
            $table->foreign('id_bahan')
                ->references('id_bahan')
                ->on('bahan')
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
        Schema::dropIfExists('history');
    }
}
