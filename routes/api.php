<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::post('register','Api\AuthController@register');
Route::post('login','Api\AuthController@login');


Route::group(['middleware'=>'auth:api'],function(){
    Route::post('logout','Api\AuthController@logout');
    Route::post('update-pass','Api\AuthController@updatePass');
    Route::get('account','Api\AuthController@show');

    //Jabatan
    Route::post('jabatan','Api\JabatanController@store');
    Route::get('jabatan','Api\JabatanController@index');
    Route::get('jabatan/{id_jabatan}','Api\JabatanController@show');
    Route::put('update-jabatan/{id_jabatan}','Api\JabatanController@update');
    Route::delete('delete-jabatan/{id_jabatan}','Api\JabatanController@destroy');

    //karyawan
    Route::post('karyawan','Api\KaryawanController@store');
    Route::put('update-karyawan/{id_karyawan}','Api\KaryawanController@update');
    Route::get('karyawan/{email}','Api\KaryawanController@findByEmail');
    Route::put('update-status-karyawan/{id_karyawan}','Api\KaryawanController@updateStatus');
    Route::get('karyawan','Api\KaryawanController@index');
    Route::get('karyawan/{id_karyawan}','Api\KaryawanController@show');
    Route::put('update-pp-karyawan/{id_karyawan}','Api\KaryawanController@updatePP');

    //Customer
    Route::post('customer','Api\CustomerController@store');
    Route::get('customer','Api\CustomerController@index');
    Route::get('customer/{id_customer}','Api\CustomerController@show');
    Route::put('update-customer/{id_customer}','Api\CustomerController@update');
    Route::post('sdel-customer/{id_customer}','Api\CustomerController@delete');
    Route::delete('delete-customer/{id_customer}','Api\CustomerController@destroy');

    //Kartu
    Route::post('kartu','Api\KartuController@store');
    Route::get('kartu','Api\KartuController@index');
    Route::get('kartu/{id_kartu}','Api\KartuController@show');
    Route::put('update-kartu/{id_kartu}','Api\KartuController@update');
    Route::post('sdel-kartu/{id_kartu}','Api\KartuController@delete');
    Route::delete('delete-kartu/{id_kartu}','Api\KartuController@destroy');

    //Meja
    Route::post('meja','Api\MejaController@store');
    Route::get('meja','Api\MejaController@index');
    Route::get('meja/{no_meja}','Api\MejaController@show');
    Route::put('update-meja/{no_meja}','Api\MejaController@update');
    Route::delete('delete-meja/{no_meja}','Api\MejaController@destroy');

     //Reservasi
     Route::post('reservasi','Api\ReservasiController@store');
     Route::get('reservasi','Api\ReservasiController@index');
     Route::get('reservasi/{id_reservasi}','Api\ReservasiController@show');
     Route::get('reservasi-by-date/{date}/{sesi}','Api\ReservasiController@showByDate');
     Route::put('update-reservasi/{id_reservasi}','Api\ReservasiController@update');
     Route::post('sdel-reservasi/{id_reservasi}','Api\ReservasiController@delete');
     Route::delete('delete-reservasi/{id_reservasi}','Api\ReservasiController@destroy');
    
     
    //Bahan
    Route::post('bahan','Api\BahanController@store');
    Route::get('bahan','Api\BahanController@index');
    Route::get('bahan/{id_bahan}','Api\BahanController@show');
    Route::put('update-bahan/{id_bahan}','Api\BahanController@update');
    Route::post('sdel-bahan/{id_bahan}','Api\BahanController@delete');
    Route::delete('delete-bahan/{id_bahan}','Api\BahanController@destroy');

    //History
    Route::post('history','Api\HistoryController@store');
    Route::get('history','Api\HistoryController@index');
    Route::get('history/{id_history}','Api\HistoryController@show');
    Route::put('update-history/{id_history}','Api\HistoryController@update');
    Route::post('sdel-history/{id_history}','Api\HistoryController@delete');
    Route::delete('delete-history/{id_history}','Api\HistoryController@destroy');

    //Menu
    Route::post('menu','Api\MenuController@store');
    Route::get('menu','Api\MenuController@index');
    Route::get('menu/{id_menu}','Api\MenuController@show');
    Route::put('update-menu/{id_menu}','Api\MenuController@update');
    Route::post('sdel-menu/{id_menu}','Api\MenuController@delete');
    Route::delete('delete-menu/{id_menu}','Api\MenuController@destroy');

    //Pesanan
    Route::post('pesanan','Api\PesananController@store');
    Route::get('pesanan','Api\PesananController@index');
    Route::get('pesanan/{id_pesanan}','Api\PesananController@show');
    Route::put('update-pesanan/{id_pesanan}','Api\PesananController@update');
    Route::post('sdel-pesanan/{id_pesanan}','Api\PesananController@delete');
    Route::delete('delete-pesanan/{id_pesanan}','Api\PesananController@destroy');

    //Detil-Pesanan
    Route::post('detil-pesanan','Api\DetilPesananController@store');
    Route::get('detil-pesanan','Api\DetilPesananController@index');
    Route::get('detil-pesanan/{id_detil_pesanan}','Api\DetilPesananController@show');
});
