<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKaryawanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('karyawan', function (Blueprint $table) {
            $table->id('id_karyawan');
            $table->string('nama_karyawan');
            $table->date('tanggal_lahir');
            $table->string('alamat_karyawan');
            $table->string('kontak_karyawan');
            $table->boolean('status_karyawan');
            $table->date('tanggal_masuk');
            $table->string('email');
            $table->string('id_jabatan');
            $table->foreign('id_jabatan')
                  ->references('id_jabatan')
                  ->on('jabatan')
                  ->onDelete('cascade');
            $table->rememberToken();
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
        Schema::dropIfExists('karyawans');
    }
}
