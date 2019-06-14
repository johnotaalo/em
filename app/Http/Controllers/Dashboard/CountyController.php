<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\County, AssesmentType;
use App\Facility;
use App\Supervision;
use App\SupervisionDataLegacy;

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
        // $facilities = Facility::where('county', $request->county)->count();
        $facilities = Supervision::select('fname')->distinct()->count();
        $pneumoniaTotals = $this->getCountyPneumoniaTotals($request->county);
        $diarrhoeaTotals = $this->getCountyDiarrhoeaTotals($request->county);
        $supervisionLegacyData = \DB::select("SELECT * FROM supervision_legacy_data_view WHERE county = '{$request->county}'");
        // $pneumoniaTotals = ['assessment' => '', 'TOTAL_CASES_AFTER_DIF' => 0];
        // $diarrhoeaTotals = ['TOTAL_CASES_AFTER_DIFF' => 0];
        // echo "<pre>";print_r($distributions);die;
    	return view('dashboard.county.breakdown')->with(['county' => $request->county, 'assessments' => $this->getAssessmentTypes($request->county), 'distributions' => $distributions, 'facilities'=> $facilities, 'pneumoniaTotals' => $pneumoniaTotals, 'diarrhoeaTotals' => $diarrhoeaTotals, 'legacy' => $supervisionLegacyData]);
    }

    function getCountyPneumoniaTotals($county){
        $sql = "SELECT
                    assessment,
                    SUM( TOTAL_CLASSIFIED ) AS TOTAL_CLASSIFIED,
                    SUM( TOTAL_CASES_AFTER_DIF ) AS TOTAL_CASES_AFTER_DIF 
                FROM
                    pneumonia_facility_classification 
                WHERE
                    county = '{$county}' 
                GROUP BY
                    assessment";
        $data = \DB::select($sql);
        return ($data) ? $data[0] : [];
    }

    function getCountyDiarrhoeaTotals($county){
        $sql = "SELECT
                    assessment,
                    SUM( classified ) AS TOTAL_CLASSIFIED,
                    SUM( classified + ( IF ( DIFFERENCE > 0, NO_CLASS_CASES + DIFFERENCE, NO_CLASS_CASES ) ) ) AS TOTAL_CASES_AFTER_DIFF 
                FROM
                    `diarrhoea_case_tx_aggreagate` 
                WHERE
                    county = '{$county}' 
                GROUP BY
                    assessment";
        $data = \DB::select($sql);
        return ($data) ? $data[0] : [];
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
