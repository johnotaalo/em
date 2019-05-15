<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\County, AssesmentType;

class CountyController extends Controller
{
    function index(){
    	return view('dashboard.county.index');
    }

    function breakdown(Request $request){
    	if (!$request->county) {
    		$county = County::where('cto_id', '!=', null)->inRandomOrder()->first();
    		$request->county = $county->county;
    	}
        $distributions = $this->getFacilityDistribution($request->county);
        // echo "<pre>";print_r($distributions);die;
    	return view('dashboard.county.breakdown')->with(['county' => $request->county, 'assessments' => $this->getAssessmentTypes($request->county), 'distributions' => $distributions]);
    }

    function getAssessmentTypes($county){
        $sql = "SELECT county, (
                CASE
                        
                        WHEN ( `supervision_data`.`assessment_type_id` = 3 ) THEN
                        concat( `t`.`assessment_type`, ' ', CONVERT ( substring_index( `supervision_data`.`period`, ' ',- ( 1 ) ) USING utf8mb4 ) ) ELSE `t`.`assessment_type` 
                    END 
                    ) AS `assessment` FROM supervision_data
                    JOIN assessment_types t ON t.id = supervision_data.assessment_type_id
                    WHERE county = '{$county}'
                    GROUP BY county, (
                CASE
                        
                        WHEN ( `supervision_data`.`assessment_type_id` = 3 ) THEN
                        concat( `t`.`assessment_type`, ' ', CONVERT ( substring_index( `supervision_data`.`period`, ' ',- ( 1 ) ) USING utf8mb4 ) ) ELSE `t`.`assessment_type` 
                    END 
                    );";

                    return \DB::select($sql);
    }

    function getFacilityDistribution($county){
        $sql = 'SELECT FACILITY_TYPE, count(SURVEY_CTO_ID) AS facilities FROM `facilities` WHERE county = "'.$county.'" GROUP BY FACILITY_TYPE';
        // die($sql);

        return \DB::select($sql);
    }
}
