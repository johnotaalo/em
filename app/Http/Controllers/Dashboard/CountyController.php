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
        // $facilities = $supervisionLegacyData = [];
        // $pneumoniaTotals = $diarrhoeaTotals = new \StdClass;
        $supervisionLegacyData = $distributions = [];
        $facilities = [];
    	if (!$request->county) {
    		$county = County::where('cto_id', '!=', null)->inRandomOrder()->first();
    		$request->county = $county->county;
            $request->county_id = $county->cto_id;
    	}else{
            $c = $request->county;
            if (!is_numeric($c)) {
                $county = County::where('county', 'LIKE', $request->county)->first();
            }else{
                $county = County::where('cto_id', $request->county)->first();
            }

            $request->county = $county->county;
            $request->county_id = $county->cto_id;
        }
        // $supervisionLegacyData = \DB::select("SELECT * FROM supervision_legacy_data_view WHERE county = '{$request->county}'");
        // dd($supervisionLegacyData);
        $distributions = $this->getFacilityDistribution($request->county);
        // dd($distributions);die;
        // $facilities = Facility::where('county', $request->county)->count();
        // $facilities = Supervision::select('fname')->distinct()->where('county', $request->county)->count();
        $facilities = Supervision::select('fname')->distinct()->where('cname', $request->county_id)->count();
        // $pneumoniaTotals = $this->getCountyPneumoniaTotals($request->county);
        // dd($pneumoniaTotals);
        // $diarrhoeaTotals = $this->getCountyDiarrhoeaTotals($request->county);
        // $supervisionLegacyData = \DB::select("SELECT * FROM supervision_legacy_data_view WHERE county = '{$request->county}'");
        $pneumoniaTotals = ['assessment' => '', 'TOTAL_CASES_AFTER_DIF' => 0];
        $diarrhoeaTotals = ['TOTAL_CASES_AFTER_DIFF' => 0];
        // echo "<pre>";print_r($distributions);die;
    	return view('dashboard.county.breakdown')->with(['county_id' => $request->county_id, 'county' => $request->county, 'assessments' => \App\DiarrhoeaCalculatedValue::distinct()->where('cname', $request->county_id)->get(['assessment']), 'distributions' => $distributions, 'facilities'=> $facilities, 'pneumoniaTotals' => $pneumoniaTotals, 'diarrhoeaTotals' => $diarrhoeaTotals, 'legacy' => $supervisionLegacyData]);
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
        // $sql = 'SELECT FACILITY_TYPE, count(SURVEY_CTO_ID) AS facilities FROM `facilities` WHERE county = "'.$county.'" GROUP BY FACILITY_TYPE';
        // die($sql);

        $sql = "SELECT f.FACILITY_TYPE, COUNT(x.facility_code) AS facilities FROM (SELECT DISTINCT(fname) as facility_code FROM supervision_data WHERE county = '{$county}') x JOIN facilities f ON f.SURVEY_CTO_ID = x.facility_code GROUP BY f.FACILITY_TYPE";

        return \DB::select($sql);
    }
}
