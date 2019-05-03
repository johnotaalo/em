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
	Route::get('facilities/breakdown', 'API\SupervisionController@getFacilitiesBreakdown');
	Route::get('county/facilities', 'API\SupervisionController@getCountyFacilities');

	Route::post('uploadData', 'API\SupervisionController@uploadCSV');
	Route::get('temporary', 'API\SupervisionController@getTemporaryData');
	Route::get('upload/{warning}', 'API\SupervisionController@uploadData');

	Route::get('counties', 'API\SupervisionController@getCounties');
	Route::get('assessmentTypes', 'API\SupervisionController@getAssessmentTypes');

	Route::get('county-facilities-supervisions', 'API\SupervisionController@getCountiesFacilitySupervision');

	Route::get('subcounties/{county}', 'API\SupervisionController@getSubcounties');
	Route::get('facilities/{subcounty}', 'API\SupervisionController@getfacilities');

	Route::get('pneumonia/classification/subcounty/{county}', 'API\SupervisionController@getSubcountyPneumoniaClassification');
	Route::get('pneumonia/treatment/subcounty/{county}', 'API\SupervisionController@getSubcountyTreatmentData');
	Route::get('pneumonia/classification/loc/{county}', 'API\SupervisionController@getLOCPneumoniaClassification');
	Route::get('pneumonia/treatment/loc/{county}', 'API\SupervisionController@getSubcountyLocTreatmentData');
	Route::get('pneumonia/classification/facility/{sub_county}', 'API\SupervisionController@getFacilityPneumoniaClassification');
	Route::get('diarrhoea/classification/subcounties/{county}', 'API\SupervisionController@getSubcountyDiarrhoeaClassification');
});
