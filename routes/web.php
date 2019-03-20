<?php

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

Route::get('/', 'Dashboard\HomeController@index');
Route::get('/home', 'Dashboard\HomeController@index');

Auth::routes();

Route::get('/dashboard/national', 'Dashboard\HomeController@index')->name('home');
Route::get('dashboard/county', 'Dashboard\CountyController@index')->name('county-dashboard');
Route::get('data/upload', 'Dashboard\DataController@uploadPage')->name('upload-page');
Route::get('data/counties', 'Dashboard\DataController@countyPage')->name('county-page');
