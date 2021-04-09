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
Route::get('/daftar-kata','DaftarKataController@index');
Route::get('/proses', 'TranslateController@proses');
Route::get('/imbuhan','ImbuhanController@index');
Route::post('/daftar-kata/update/{id}','DaftarKataController@update');
Route::get('/debug', 'TranslateController@debug');
