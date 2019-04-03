<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('data')->group(function(){
	Route::get('classification', 'API\SupervisionController@getClassificationData');
	Route::get('treatments/pneumonia', 'API\SupervisionController@getPneumoniaData');
	Route::get('treatments/diarrhoea', 'API\SupervisionController@getDiarrhoeaTreatments');
	Route::get('count/facilities', 'API\SupervisionController@getFacilitiesCount');
	Route::get('count/counties', 'API\SupervisionController@getCountiesCount');
	Route::get('county/diarrhoea/classification', 'API\SupervisionController@getCountyData');
	Route::get('county/diarrhoea/treatment', 'API\SupervisionController@getCountyDiarrhoeaTreatments');
	Route::get('county/pneumonia/treatment', 'API\SupervisionController@getCountyPneumoniaTreatments');
	Route::get('countyData', 'API\SupervisionController@getCountyCoverage');

	Route::post('uploadData', 'API\SupervisionController@uploadCSV');
	Route::get('temporary', 'API\SupervisionController@getTemporaryData');
	Route::get('upload', 'API\SupervisionController@uploadData');

	Route::get('counties', 'API\SupervisionController@getCounties');
	Route::get('assessmentTypes', 'API\SupervisionController@getAssessmentTypes');
});
