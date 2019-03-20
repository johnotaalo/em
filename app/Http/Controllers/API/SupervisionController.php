<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SupervisionController extends Controller
{
    function counties(){
    }

    function getClassificationData(){
    	$data = \DB::table('supervision_data_classification')->select(\DB::raw("SUM( PN_CLASSIFICATION ) AS SUM_PN_CLASSIFICATION, SUM( PN_NO_CLASSIFICATION ) AS SUM_PN_NO_CLASSIFICATION, SUM( DIA_CLASSIFICATION ) AS SUM_DIA_CLASSIFICATION, SUM( DIA_NO_CLASSIFICATION ) AS SUM_DIA_NO_CLASSIFICATION"))->first();


    	$response = [];

    	$response = [
    		'pneumonia'	=>	[
    			'total_cases'	=>	$data->SUM_PN_CLASSIFICATION,
    			'no_class'		=>	$data->SUM_PN_NO_CLASSIFICATION
    		],
    		'diarrhoea'	=>	[
    			'total_cases'	=>	$data->SUM_DIA_CLASSIFICATION,
    			'no_class'		=>	$data->SUM_DIA_NO_CLASSIFICATION
    		]
    	];

    	return $response;
    }
}
