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

Route::get('/', 'Dashboard\HomeController@index')->name('index');
Route::get('/home', 'Dashboard\HomeController@index')->name('home-index');

Auth::routes();

Route::get('/dashboard/national', 'Dashboard\HomeController@index')->name('home');
Route::get('dashboard/county', 'Dashboard\CountyController@index')->name('county-dashboard');
Route::get('dashboard/county/breakdown/{county?}/{county_id?}', 'Dashboard\CountyController@breakdown')->name('county-breakdown');
Route::get('data/upload', 'Dashboard\DataController@uploadPage')->name('upload-page');
Route::get('data/upload/cancel', 'Dashboard\DataController@cancelUpload')->name('upload-page');
Route::get('data/counties', 'Dashboard\DataController@countyPage')->name('county-page');

Route::get('data/facilities', 'Dashboard\DataController@facilitiesPage')->name('facilities');
Route::get('data/pull', 'Dashboard\DataController@pullCalculatedData');

Route::get('directory', 'Dashboard\DataController@directory')->name('directory');

Route::get('country', 'Dashboard\HomeController@countryOverview')->name('country-overview');
Route::get('overview/county', 'Dashboard\HomeController@countyOverview')->name('county-overview');

Route::prefix('user-management')->group(function(){
	Route::get('/', 'Dashboard\UserManagement@index')->name('user-management');
	Route::get('/add', 'Dashboard\UserManagement@add')->name('user-management-add');
	Route::get('/edit/{id}', 'Dashboard\UserManagement@edit')->name('user-management-edit');
});