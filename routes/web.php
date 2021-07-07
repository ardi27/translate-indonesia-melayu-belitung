<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'TranslateController@index');
Route::get('/proses', 'TranslateController@proses');
Route::prefix('daftar-kata')->group(function () {
    Route::get('/', 'DaftarKataController@index');
    Route::post('/update/{id}', 'DaftarKataController@update');
    Route::post('/tambah', 'DaftarKataController@store');
});
Route::prefix('imbuhan')->group(function () {
    Route::get('/', 'ImbuhanController@index');
    Route::post('/update/{id}', 'ImbuhanController@update');
    Route::post('/tambah', 'ImbuhanController@store');
});
Route::get('/debug', 'TranslateController@debug');
Route::get('/konfigurasi', 'KonfigurasiController@index');
Route::post('/konfigurasi/{id}', 'KonfigurasiController@setKonfigurasi');
Route::get('/env', function () {
    echo env('DB_USERNAME');
});
