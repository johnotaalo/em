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

Route::prefix('users')->group(function(){
	Route::get('/', 'API\UserManagement@index');
	Route::get('/{id}', 'API\UserManagement@getUser');
	Route::post('/add', 'API\UserManagement@add');
	Route::put('/edit/{id}', 'API\UserManagement@editUser');
	Route::post('/validate/email', 'API\UserManagement@validateEmail');
	Route::post('/update/status', 'API\UserManagement@updateUserStatus');
	Route::put('/password/reset', 'API\UserManagement@resetPassword');
});

Route::prefix('data')->group(function(){
	Route::get('classification', 'API\SupervisionController@getClassificationData');
	Route::get('treatments/pneumonia', 'API\SupervisionController@getPneumoniaData');
	Route::get('treatments/diarrhoea', 'API\SupervisionController@getDiarrhoeaTreatments');
	Route::get('count/facilities', 'API\SupervisionController@getFacilitiesCount');
	Route::get('count/counties', 'API\SupervisionController@getCountiesCount');
	Route::get('count/assessments', 'API\SupervisionController@getAssessmentsCount');
	Route::get('county/diarrhoea/classification', 'API\SupervisionController@getCountyData');
	Route::get('county/diarrhoea/treatment', 'API\SupervisionController@getCountyDiarrhoeaTreatments');
	Route::get('county/pneumonia/treatment', 'API\SupervisionController@getCountyPneumoniaTreatments');
	Route::get('countyData', 'API\SupervisionController@getCountyCoverage');
	Route::get('facilities/breakdown', 'API\SupervisionController@getFacilitiesBreakdown');
	Route::get('county/facilities', 'API\SupervisionController@getCountyFacilities');

	Route::post('uploadData', 'API\SupervisionController@uploadCSV');
	Route::get('temporary', 'API\SupervisionController@getTemporaryData');
	Route::get('temporary/legacy', 'API\SupervisionController@getLegacyTemporaryData');
	Route::get('upload/{warning}', 'API\SupervisionController@uploadData');

	Route::get('counties', 'API\SupervisionController@getCounties');
	Route::get('counties-and-subcounties', 'API\SupervisionController@getCountiesSubcounties');
	Route::get('assessmentTypes', 'API\SupervisionController@getAssessmentTypes');

	Route::get('county-facilities-supervisions', 'API\SupervisionController@getCountiesFacilitySupervision');

	Route::get('subcounties/{county}', 'API\SupervisionController@getSubcounties');
	Route::get('facilities/{subcounty}', 'API\SupervisionController@getfacilities');

	Route::get('pneumonia/classification/subcounty/{county}', 'API\SupervisionController@getSubcountyPneumoniaClassification');
	Route::get('pneumonia/treatment/subcounty/{county}', 'API\SupervisionController@getSubcountyTreatmentData');
	Route::get('pneumonia/classification/loc/{county}', 'API\SupervisionController@getLOCPneumoniaClassification');
	Route::get('pneumonia/treatment/loc/{county}', 'API\SupervisionController@getSubcountyLocTreatmentData');
	Route::get('pneumonia/classification/facility/{sub_county}', 'API\SupervisionController@getFacilityPneumoniaClassification');
	// Route::get('diarrhoea/classification/subcounties/{county}', 'API\SupervisionController@getSubcountyDiarrhoeaClassification');

	Route::get('pneumonia/treatment/facilities/{subcounty}', 'API\SupervisionController@getFacilityPneumoniaTreatment');
	Route::get('pneumonia/classification/locfacility/{subcounty}', 'API\SupervisionController@getFacilityPneumoniaClassificationX');
	Route::get("pneumonia/treatment/facilitydata/loc/{subcounty}", "API\SupervisionController@getFacilityPneumoniaTreatmentData");

	Route::get('diarrhoea/classification/subcounties/{county}', 'API\SupervisionController@getDiarrhoaClassificationData');

	Route::get('diarrhoea/treatment/subcounty/{county}', 'API\SupervisionController@getSubcountyDiarrhoeaTreatmentData');
	Route::get('diarrhoea/classification/loc/{county}', 'API\SupervisionController@getLOCDiarrhoeaClassification');
	Route::get('diarrhoea/treatment/loc/{county}', 'API\SupervisionController@getLOCDiarrhoeaTreatment');
	Route::get('diarrhoea/classification/facility/{subcounty}', 'API\SupervisionController@getFacilityDiarrhoeaClassification');
	Route::get('diarrhoea/treatment/facilities/{subcounty}', 'API\SupervisionController@getFacilityDiarrhoeaTreatment');
	Route::get('diarrhoea/classification/facility/loc/{subcounty}', 'API\SupervisionController@getDiarrhoeaSubcountyLocClassification');

	Route::get('diarrhoea/treatment/scloc/{subcounty}', 'API\SupervisionController@getDiarrhoeaLocTreatmentData');

	Route::get('county/breakdown/{county_id}', 'API\SupervisionController@getCountyBreakdownData');
	Route::get('national', 'API\SupervisionController@getNationalData');

	Route::get('facility-listing', 'API\FacilityController@search');

	Route::get('facility-types', 'Api\FacilityController@getFacilityTypes');	
});

Route::post('facilities/add', 'API\FacilityController@store');
