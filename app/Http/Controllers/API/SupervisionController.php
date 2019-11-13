<?php

namespace App\Http\Controllers\API;

ini_set('max_execution_time', -1);
use App\Http\Controllers\Controller;
use App\SupervisionDataUploadTmp as SPUploadTmp;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\SupervisionDataImport;
use App\Imports\SupervisionLegacyImport;
use Illuminate\Http\Request;
use App\SupervisionUpload as Upload;

class SupervisionController extends Controller
{
    function counties(){
    }

    function getCountyBreakdownData(Request $request){
        $data = \App\PneumoniaCalculatedValue::where('cname', $request->county_id)->get();
        $cleanedData = [];

        foreach ($data as $d) {
            $cleanedData[$d->assessment][] = $d;
        }

        return $cleanedData;
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

    function getCounties(){
        $counties = \App\County::orderBy('county', 'ASC')->get();

        return $counties;
    }


    function getAssessmentTypes(){
        return \App\AssessmentType::all();
    }

    function getPneumoniaData(){
        $sql = "SELECT
SUM(AMOXDT) AS AMOXDT,
SUM(AMOX_SYRUP) AS AMOX_SYRUP,
    SUM( OXYGEN ) AS OXYGEN,
    SUM(CTX) AS CTX,
SUM(INJECTABLES) AS INJECTABLES,
SUM(OTHER) AS OTHER,
SUM(NOTX) AS NOTX
FROM
    (
    SELECT
        t.county,
        SUM( OXYGEN ) AS OXYGEN,
        SUM( AMOXDT ) AS AMOXDT,
        SUM( AMOX_SYRUP ) AS AMOX_SYRUP,
        SUM( CTX ) AS CTX,
        SUM( BENZ ) AS BENZ,
        SUM( BENZ_GENT ) AS BENZ_GENT,
        SUM( GENT ) AS GENT,
        ( SUM( BENZ ) + SUM( BENZ_GENT ) + SUM( GENT ) ) AS INJECTABLES,
        ( SUM( ANTI_OTHER ) ) AS OTHER,
        c.NOTX 
    FROM
        `pneumonia_treatment_data` t
        JOIN ( SELECT county, SUM( NOTX_AFTER_DIF ) AS NOTX FROM pneumonia_tx_classification_agg_combined GROUP BY county ) c ON c.county = t.county 
    GROUP BY
    t.county 
    ) v";
    // echo $sql;die;
    $data = \DB::select($sql);
    	// $data = \DB::table('supervision_data')->select(\DB::raw("SUM(IFNULL(sev_oxygen, 0) + IFNULL(pn_oxygen, 0) + IFNULL(noc_oxygen, 0) + IFNULL(noclass_oxygen, 0)) AS `OXYGEN` ,SUM(IFNULL(sev_amoxdt, 0) + IFNULL(pn_amox, 0) + IFNULL(noc_amox, 0) + IFNULL(noclass_amox, 0)) AS `AMOXDT`, SUM(IFNULL(sev_amoxsy, 0) + IFNULL(pn_amoxsy, 0) + IFNULL(noc_amoxsy, 0) + IFNULL(noclass_amoxsy, 0)) AS `AMOX_SYRUP`, SUM(IFNULL(sev_ctx, 0) + IFNULL(pn_ctx, 0) + IFNULL(noc_ctx, 0) + IFNULL(noclass_ctx, 0)) AS `CTX`,  SUM(IFNULL(sev_gent, 0) + IFNULL(pn_gent, 0) + IFNULL(noc_gent, 0) + IFNULL(noclass_gent, 0) + IFNULL(sev_benz, 0) + IFNULL(pn_benz, 0) + IFNULL(noc_benz, 0) + IFNULL(noclass_benz, 0) + IFNULL(sev_benzgent, 0) + IFNULL(pn_benzgent, 0) + IFNULL(noc_benzgent, 0) + IFNULL(noclass_benzgent, 0)) AS `INJECTABLES`, SUM(IFNULL(sev_anti, 0) + IFNULL(pn_anti, 0) + IFNULL(noc_anti, 0) + IFNULL(noclass_anti, 0) + IFNULL(sev_other, 0) + IFNULL(pn_other, 0) + IFNULL(noc_other, 0) + IFNULL(noclass_other, 0)) AS `OTHER`, SUM(IFNULL(sev_notx, 0) + IFNULL(pn_notx, 0) + IFNULL(noc_notx, 0) + IFNULL(noclass_notx, 0)) AS `NOTX`"))->first();

    	$reponse = [];

    	foreach ($data[0] as $key => $value) {
    		$response[$key] = $value;
    	}

    	return $response;
    }

    function getDiarrhoeaTreatments(){
    	$data = \DB::table('supervision_data')->select(\DB::raw("SUM(IFNULL(d_shock_zinc, 0) + IFNULL(d_shock_ors, 0) + + IFNULL(d_nodehy_ors, 0) + IFNULL(d_nodehy_zinc, 0) +  IFNULL(d_dys_zinc, 0) +  IFNULL(d_dys_ors, 0) + IFNULL(d_noclass_ors, 0) + IFNULL(d_noclass_zinc, 0)) AS `DIARRHOEA_ZINC_ORS`"))->first();

    	$response = [];

    	foreach ($data as $key => $value) {
    		$response[$key] = $value;
    	}

    	return $response;
    }

    function getFacilitiesCount(){
    	$data = \DB::table('supervision_data')->distinct('fname')->count('fname');

    	return $data;
    }

    function getFacilitiesBreakdown(){
        $data = \App\Supervision::distinct('fname')->with('facility')->get();

        $response = [];

        foreach ($data as $d) {
            if(isset($d->facility->FACILITY_TYPE)){
                $response[$d->facility->FACILITY_TYPE][] = $d->facility->FACILITY_NAME;
            }else{
                $response['UNKNOWN'][] = $d->facility_name;
            }
        }

        return $response;
    }

    function getCountiesCount(){
    	$data = \DB::table('supervision_data')->distinct('county')->count('county');

    	return $data;
    }

    function getCountyData(){
    	$data = \DB::table('supervision_data_classification')->select(\DB::raw("county, SUM( PN_CLASSIFICATION ) AS SUM_PN_CLASSIFICATION, SUM( PN_NO_CLASSIFICATION ) AS SUM_PN_NO_CLASSIFICATION, SUM( DIA_CLASSIFICATION ) AS SUM_DIA_CLASSIFICATION, SUM( DIA_NO_CLASSIFICATION ) AS SUM_DIA_NO_CLASSIFICATION"))->groupBy('county')->get();
    	return $data;
    }

    function getCountyDiarrhoeaTreatments(){
    	$data = \DB::table('supervision_data')->select(\DB::raw("county, SUM(IFNULL(d_shock_zinc, 0) + IFNULL(d_shock_ors, 0) + + IFNULL(d_nodehy_ors, 0) + IFNULL(d_nodehy_zinc, 0) +  IFNULL(d_dys_zinc, 0) +  IFNULL(d_dys_ors, 0) + IFNULL(d_noclass_ors, 0) + IFNULL(d_noclass_zinc, 0)) AS `DIARRHOEA_ZINC_ORS`"))->groupBy('county')->get();

    	return $data;
    }

    function getCountyPneumoniaTreatments(){
    	$data = \DB::table('supervision_data')->select(\DB::raw("county, SUM(IFNULL(sev_amoxdt, 0) + IFNULL(pn_amox, 0) + IFNULL(noc_amox, 0) + IFNULL(noclass_amox, 0)) AS `AMOXDT`, SUM(IFNULL(sev_ctx, 0) + IFNULL(pn_ctx, 0) + IFNULL(noc_ctx, 0) + IFNULL(noclass_ctx, 0)) AS `CTX`, SUM(IFNULL(sev_amoxsy, 0) + IFNULL(pn_amoxsy, 0) + IFNULL(noc_amoxsy, 0) + IFNULL(noclass_amoxsy, 0)) AS `AMOX_SYRUP`, SUM(IFNULL(sev_other, 0) + IFNULL(pn_other, 0) + IFNULL(noc_other, 0) + IFNULL(noclass_other, 0) + IFNULL(sev_oxygen, 0) + IFNULL(pn_oxygen, 0) + IFNULL(noc_oxygen, 0) + IFNULL(noclass_oxygen, 0) + IFNULL(sev_gent, 0) + IFNULL(pn_gent, 0) + IFNULL(noc_gent, 0) + IFNULL(noclass_gent, 0) + IFNULL(sev_benz, 0) + IFNULL(pn_benz, 0) + IFNULL(noc_benz, 0) + IFNULL(noclass_benz, 0)) AS `INJECTABLES`"))->groupBy('county')->get();

    	return $data;
    }

    function uploadCSV(Request $request){
        // echo "<pre>";print_r($request->input());die;
        
        $counties = "";
        $countyList = [];
        foreach ($request->input('county') as $key => $value) {
            if (is_integer($key)) {
                $countyList[] = $value['value'];
            }else{
                $counties = $value;
            }
        }

        $counties = (count($countyList)) ? implode(", ", $countyList) : $counties;

        $path =$request->file->store('data');
        $is_legacy = ($request->input('isLegacyData') == "yes") ? true : false;
        // var_dump($is_legacy);die;

        $upload = Upload::create([ "counties" => $counties, "path" => $path, "is_legacy" => $is_legacy ]);

        $period = $request->input('duration')['month'] . " " . $request->input('duration')['year'];

        if(!$is_legacy){
            Excel::import(new SupervisionDataImport($upload->id, $request->input('assessmentType'), $period), $path);
        }else{
            Excel::import(new SupervisionLegacyImport($upload->id, $request->input('assessmentType'), $period), $path);
        }
    }

    function createCountiesData($data){
        return $data['value'];
    }

    function getTemporaryData(){
        $data = [];
        $data['temporaryData'] = SPUploadTmp::all();
        $upload = \App\SupervisionUpload::orderBy('id', 'DESC')->first();
        $data['upload'] = $upload;
        return $data;
    }

    function getLegacyTemporaryData(){
        $data = [];
        $data['temporaryData'] = \App\SupervisionDataLegacyTmp::all();
        $upload = \App\SupervisionUpload::orderBy('id', 'DESC')->first();
        $data['upload'] = $upload;
        return $data;
    }

    function uploadData(Request $request){
        $legacyTmpData = \App\SupervisionDataLegacyTmp::count();
        
        if($legacyTmpData > 0){
            $tempData = \App\SupervisionDataLegacyTmp::exclude(['created_at', 'updated_at', 'upload_id']);
        }else{
            $tempData = SPUploadTmp::exclude(['created_at', 'updated_at', 'upload_id']);
        }

        if($request->warning == "true"){
            $tempData = $tempData->limit(500);
        }

        $tempIDs = $tempData->pluck("id");
        $tempData = $tempData->get()->toArray();
        // dd($tempIDs);
        // echo "<pre>";print_r($tempData);die;

        foreach($tempData as $key => $value) {
            unset($tempData[$key]['id']);
        }

        // dd($tempData);
        if($legacyTmpData > 0){
            \DB::table('supervision_data_legacy')->insert($tempData);

            if($request->warning == "true"){
                \App\SupervisionDataLegacyTmp::destroy($tempIDs);
            }else{
                \App\SupervisionDataLegacyTmp::query()->truncate();
            }
        }else{
            \DB::table('supervision_data')->insert($tempData);

            if($request->warning == "true"){
                SPUploadTmp::destroy($tempIDs);
            }else{
                SPUploadTmp::query()->truncate();
            }
        }

        return ['status' => 'success'];
    }

    function getCountyCoverage(){
        $counties = \DB::table('supervision_data')->distinct('county')->pluck('county');

        return $counties;
    }

    function getCountyFacilities(){
        $counties = \App\County::where('cto_id', '!=', NULL)->with('facilities')->get();

        return $counties;
    }

    function getCountiesFacilitySupervision(){
        $counties = \App\County::where('cto_id', '!=', NULL)->with('supervisions', 'facilities')->get();

        return $counties;
    }

    function getSubcounties(Request $request){
        $subcounties = \DB::select("SELECT DISTINCT(sub_county) FROM supervision_data WHERE county = '{$request->county}';");

        return $subcounties;
    }

    function getfacilities(Request $request){
        $facilities = \DB::select("SELECT DISTINCT(facility_name) FROM supervision_data WHERE sub_county = '{$request->subcounty}';");

        return $facilities;
    }


    function getSubcountyPneumoniaClassification(Request $request){
        $sql = "SELECT assessment, sub_county, SUM(TOTAL_CLASSIFIED) AS TOTAL_CLASSIFIED, SUM(TOTAL_CASES_AFTER_DIF) AS TOTAL_CASES_AFTER_DIF FROM pneumonia_facility_classification WHERE county = '{$request->county}' GROUP BY sub_county, assessment";
        // $oldSql = "SELECT sub_county, TOTAL_CLASSIFIED, TOTAL_CASES_AFTER_DIF FROM `pneumonia_subcounty_classification` WHERE county = '{$request->county}';"
        // die($sql);
        $classification = \DB::select($sql);

        return $classification;
    }

    function getLOCPneumoniaClassification(Request $request){
        $sql = "SELECT county, FACILITY_TYPE, assessment, SUM( TOTAL_CLASSIFIED ) AS TOTAL_CLASSIFIED, SUM( TOTAL_CASES_AFTER_DIF ) AS TOTAL_CASES_AFTER_DIF FROM `pneumonia_facility_classification` WHERE county = '{$request->county}' GROUP BY county, FACILITY_TYPE, assessment";
        // $oldSql = "SELECT FACILITY_TYPE, SUM(TOTAL_CLASSIFIED) AS TOTAL_CLASSIFIED, SUM(TOTAL_CASES_AFTER_DIF) AS TOTAL_CASES_AFTER_DIF FROM pneumonia_loc_subcounty_classification WHERE county = '{$request->county}' GROUP BY FACILITY_TYPE";

        // die($sql);

        $classification = \DB::select($sql);

        return $classification;
    }

    function getFacilityPneumoniaClassification(Request $request){
        $sql = "SELECT * FROM pneumonia_facility_classification WHERE sub_county = '{$request->sub_county}'";
        $classification = \DB::select($sql);

        return $classification;
    }

    function getSubcountyTreatmentData(Request $request){
        $sql = "SELECT sub_county, assessment,
        SUM(AMOXDT) AS AMOXDT,
        SUM(AMOX_SYRUP) AS AMOX_SYRUP,
            SUM( OXYGEN ) AS OXYGEN,
            SUM(CTX) AS CTX,
        SUM(INJECTABLES) AS INJECTABLES,
        SUM(OTHER) AS OTHER,
        SUM(NOTX) AS NOTX
        FROM
            (
            SELECT
                                c.assessment,
                t.sub_county,
                SUM( OXYGEN ) AS OXYGEN,
                SUM( AMOXDT ) AS AMOXDT,
                SUM( AMOX_SYRUP ) AS AMOX_SYRUP,
                SUM( CTX ) AS CTX,
                SUM( BENZ ) AS BENZ,
                SUM( BENZ_GENT ) AS BENZ_GENT,
                SUM( GENT ) AS GENT,
                ( SUM( BENZ ) + SUM( BENZ_GENT ) + SUM( GENT ) ) AS INJECTABLES,
                ( SUM( ANTI_OTHER ) ) AS OTHER,
                c.NOTX 
            FROM
                `pneumonia_facility_treatment_data` t
                JOIN ( SELECT sub_county, assessment, SUM( NOTX_AFTER_DIF ) AS NOTX FROM pneumonia_facility_tx_class_facility_agg GROUP BY sub_county ) c ON c.sub_county = t.sub_county
                WHERE t.county = '{$request->county}' 
            GROUP BY
            t.sub_county 
            ) v
                        GROUP BY sub_county, assessment";
            // echo $sql;die;
        $data = \DB::select($sql);

        // $reponse = [];

        // foreach ($data as $key => $value) {
        //     $response[$key] = $value;
        // }

        // echo "<pre>";print_r($response);die;
        $cleanedData = [];

        foreach ($data as $d) {
            $cleanedData[$d->assessment][] = $d;
        }

        return $cleanedData;
    }

    function getSubcountyDiarrhoeaTreatmentData(Request $request){
        $sql = "SELECT
                    sub_county,
                    assessment,
                    SUM( ANTIBIOTICS ) AS ANTIBIOTICS,
                    SUM(IV) AS IV,
                    SUM(COP) AS COP,
                    SUM(ZINC) AS ZINC,
                    SUM(ORS) AS ORS,
                    SUM(OTHER) AS OTHER,
                    sum(IF (DIFFERENCE < 0, NOTX - DIFFERENCE, NOTX)) AS NOTX
                FROM
                    `diarrhoea_case_tx_aggreagate` 
                WHERE
                    county = '{$request->county}' 
                GROUP BY
                    sub_county,
                    assessment";

        $data = \DB::select($sql);
        $cleanedData = [];

        foreach ($data as $d) {
            $cleanedData[$d->assessment][] = $d;
        }

        return $cleanedData;
    }

    function getFacilityPneumoniaTreatment(Request $request){
        // $sql = "SELECT fname, facility_name, sub_county, assessment,
        // SUM(AMOXDT) AS AMOXDT,
        // SUM(AMOX_SYRUP) AS AMOX_SYRUP,
        //     SUM( OXYGEN ) AS OXYGEN,
        //     SUM(CTX) AS CTX,
        // SUM(INJECTABLES) AS INJECTABLES,
        // SUM(OTHER) AS OTHER,
        // SUM(NOTX) AS NOTX
        // FROM
        //     (
        //     SELECT
        //         t.fname,
        //         t.facility_name,
        //                         c.assessment,
        //         t.sub_county,
        //         SUM( OXYGEN ) AS OXYGEN,
        //         SUM( AMOXDT ) AS AMOXDT,
        //         SUM( AMOX_SYRUP ) AS AMOX_SYRUP,
        //         SUM( CTX ) AS CTX,
        //         SUM( BENZ ) AS BENZ,
        //         SUM( BENZ_GENT ) AS BENZ_GENT,
        //         SUM( GENT ) AS GENT,
        //         ( SUM( BENZ ) + SUM( BENZ_GENT ) + SUM( GENT ) ) AS INJECTABLES,
        //         ( SUM( ANTI_OTHER ) ) AS OTHER,
        //         c.NOTX 
        //     FROM
        //         `pneumonia_facility_treatment_data` t
        //         JOIN ( SELECT sub_county, fname, facility_name, assessment, SUM( NOTX_AFTER_DIF ) AS NOTX FROM pneumonia_facility_tx_class_facility_agg GROUP BY sub_county ) c ON c.sub_county = t.sub_county
        //         WHERE t.sub_county = '{$request->subcounty}' 
        //     GROUP BY
        //     t.fname 
        //     ) v
        //                 GROUP BY fname, assessment";

                        // die($sql);
        $sql= "SELECT
t.assessment,
    f.facility_name,
    SUM( OXYGEN ) AS OXYGEN,
    SUM( AMOXDT ) AS AMOXDT,
    SUM( AMOX_SYRUP ) AS AMOX_SYRUP,
    SUM( CTX ) AS CTX,
    SUM( BENZ ) AS BENZ,
    SUM( BENZ_GENT ) AS BENZ_GENT,
    SUM( GENT ) AS GENT,
    ( SUM( BENZ ) + SUM( BENZ_GENT ) + SUM( GENT ) ) AS INJECTABLES,
    ( SUM( ANTI_OTHER ) ) AS OTHER,
   ( SUM(fa.NOTX_AFTER_DIF) ) AS NOTX
FROM
    `pneumonia_facility_treatment_data` t
    LEFT JOIN facilities f ON t.fname = f.SURVEY_CTO_ID
    LEFT JOIN pneumonia_facility_tx_class_facility_agg fa ON fa.fname = t.fname
    WHERE
    f.sub_county = '{$request->subcounty}'
    GROUP BY f.SURVEY_CTO_ID, assessment";
        $data = \DB::select($sql);
        $cleanedData = [];

        foreach ($data as $d) {
            $cleanedData[$d->assessment][] = $d;
        }

        return $cleanedData;
    }

    function getSubcountyLocTreatmentData(Request $request){
        $sql = "SELECT
t.assessment,
    f.FACILITY_TYPE,
    SUM( OXYGEN ) AS OXYGEN,
    SUM( AMOXDT ) AS AMOXDT,
    SUM( AMOX_SYRUP ) AS AMOX_SYRUP,
    SUM( CTX ) AS CTX,
    SUM( BENZ ) AS BENZ,
    SUM( BENZ_GENT ) AS BENZ_GENT,
    SUM( GENT ) AS GENT,
    ( SUM( BENZ ) + SUM( BENZ_GENT ) + SUM( GENT ) ) AS INJECTABLES,
    ( SUM( ANTI_OTHER ) ) AS OTHER,
   ( SUM(fa.NOTX_AFTER_DIF) ) AS NOTX
FROM
    `pneumonia_facility_treatment_data` t
    LEFT JOIN facilities f ON t.fname = f.SURVEY_CTO_ID
    LEFT JOIN pneumonia_facility_tx_class_facility_agg fa ON fa.fname = t.fname
    WHERE
    f.county = '{$request->county}'
    GROUP BY f.FACILITY_TYPE, assessment";

                        // die($sql);

        $data = \DB::select($sql);
        $cleanedData = [];

        foreach ($data as $d) {
            $cleanedData[$d->assessment][] = $d;
        }

        return $cleanedData;
    }

    function getSubcountyDiarrhoeaClassification(){
        $sql = "SELECT
    `supervision_data`.`county` AS `county`,
    `supervision_data`.`sub_county` AS `sub county`,
    (
    CASE
            
            WHEN ( `supervision_data`.`assessment_type_id` = 3 ) THEN
            concat( `t`.`assessment_type`, ' ', CONVERT ( substring_index( `supervision_data`.`period`, ' ',- ( 1 ) ) USING utf8mb4 ) ) ELSE `t`.`assessment_type` 
        END 
        ) AS `assessment`,
    (
        ( ifnull( `supervision_data`.`sev_cases`, 0 ) + ifnull( `supervision_data`.`pneu_cases`, 0 ) ) + ifnull( `supervision_data`.`noc_cases`, 0 ) 
    ) AS `PN_CLASSIFICATION`,
    `supervision_data`.`noclass_cases` AS `PN_NO_CLASSIFICATION`,
    (
        (
            (
                ( ifnull( `supervision_data`.`d_shock_cases`, 0 ) + ifnull( `supervision_data`.`d_sev_cases`, 0 ) ) + ifnull( `supervision_data`.`d_some_cases`, 0 ) 
            ) + ifnull( `supervision_data`.`d_nodehy_cases`, 0 ) 
        ) + ifnull( `supervision_data`.`d_dys_cases`, 0 ) 
    ) AS `DIA_CLASSIFICATION`,
    `supervision_data`.`d_noclass_cases` AS `DIA_NO_CLASSIFICATION` 
FROM
    `supervision_data`
    JOIN assessment_types t ON t.id = supervision_data.assessment_type_id
    GROUP BY sub_county, (
    CASE
            
            WHEN ( `supervision_data`.`assessment_type_id` = 3 ) THEN
            concat( `t`.`assessment_type`, ' ', CONVERT ( substring_index( `supervision_data`.`period`, ' ',- ( 1 ) ) USING utf8mb4 ) ) ELSE `t`.`assessment_type` 
        END 
        )";

        $classification = \DB::select($sql);

        return $classification;
    }

    function getFacilityPneumoniaClassificationX(Request $request){
        $sql = "SELECT sub_county, FACILITY_TYPE, assessment, SUM( TOTAL_CLASSIFIED ) AS TOTAL_CLASSIFIED, SUM( TOTAL_CASES_AFTER_DIF ) AS TOTAL_CASES_AFTER_DIF FROM `pneumonia_facility_classification` WHERE sub_county = '{$request->subcounty}' GROUP BY sub_county, FACILITY_TYPE, assessment";
        // $oldSql = "SELECT FACILITY_TYPE, SUM(TOTAL_CLASSIFIED) AS TOTAL_CLASSIFIED, SUM(TOTAL_CASES_AFTER_DIF) AS TOTAL_CASES_AFTER_DIF FROM pneumonia_loc_subcounty_classification WHERE county = '{$request->county}' GROUP BY FACILITY_TYPE";

        // die($sql);

        $classification = \DB::select($sql);

        // echo "<pre>";print_r($classification);die;

        return $classification;   
    }

    function getFacilityPneumoniaTreatmentData(Request $request){
        $sql = "SELECT
t.assessment,
    f.FACILITY_TYPE,
    SUM( OXYGEN ) AS OXYGEN,
    SUM( AMOXDT ) AS AMOXDT,
    SUM( AMOX_SYRUP ) AS AMOX_SYRUP,
    SUM( CTX ) AS CTX,
    SUM( BENZ ) AS BENZ,
    SUM( BENZ_GENT ) AS BENZ_GENT,
    SUM( GENT ) AS GENT,
    ( SUM( BENZ ) + SUM( BENZ_GENT ) + SUM( GENT ) ) AS INJECTABLES,
    ( SUM( ANTI_OTHER ) ) AS OTHER,
   ( SUM(fa.NOTX_AFTER_DIF) ) AS NOTX
FROM
    `pneumonia_facility_treatment_data` t
    LEFT JOIN facilities f ON t.fname = f.SURVEY_CTO_ID
    LEFT JOIN pneumonia_facility_tx_class_facility_agg fa ON fa.fname = t.fname
    WHERE
    f.sub_county = '{$request->subcounty}'
    GROUP BY f.FACILITY_TYPE, assessment";

                        // die($sql);

        $data = \DB::select($sql);
        $cleanedData = [];

        foreach ($data as $d) {
            $cleanedData[$d->assessment][] = $d;
        }

        return $cleanedData;
    }

    function getDiarrhoaClassificationData(Request $request){
        $sql = "SELECT
                    sub_county,
                    assessment,
                    SUM(classified) AS TOTAL_CLASSIFIED,
                    SUM( NO_CLASS_CASES ) AS NO_CLASS_CASES,
                    SUM( classified + ( IF ( DIFFERENCE > 0, NO_CLASS_CASES + DIFFERENCE, NO_CLASS_CASES ) ) ) AS TOTAL_CASES_AFTER_DIFF,
                    SUM( IF ( DIFFERENCE > 0, NOTX + DIFFERENCE, NOTX ) ) AS NOTX_AFTER_DIFF 
                FROM
                    `diarrhoea_case_tx_aggreagate` 
                WHERE
                    county = '{$request->county}'
                GROUP BY
                    sub_county,
                    assessment ";

        $data = \DB::select($sql);

        return $data;
    }

    function getLOCDiarrhoeaClassification(Request $request){
        $sql = "SELECT
                    assessment,
                    FACILITY_TYPE,
                    SUM(classified) AS TOTAL_CLASSIFIED,
                    SUM( NO_CLASS_CASES ) AS NO_CLASS_CASES,
                    SUM( classified + ( IF ( DIFFERENCE > 0, NO_CLASS_CASES + DIFFERENCE, NO_CLASS_CASES ) ) ) AS TOTAL_CASES_AFTER_DIFF,
                    SUM( IF ( DIFFERENCE < 0, NOTX + abs( DIFFERENCE ), NOTX ) ) AS NOTX_AFTER_DIFF 
                FROM
                    `diarrhoea_case_tx_aggreagate` 
                WHERE
                    county = '{$request->county}'
                GROUP BY
                    FACILITY_TYPE,
                    assessment ";

        // die($sql);

        $data = \DB::select($sql);

        return $data;
    }

    function getLOCDiarrhoeaTreatment(Request $request){
        $sql = "SELECT
                    FACILITY_TYPE,
                    assessment,
                    SUM( ANTIBIOTICS ) AS ANTIBIOTICS,
                    SUM(IV) AS IV,
                    SUM(COP) AS COP,
                    SUM(ZINC) AS ZINC,
                    SUM(ORS) AS ORS,
                    SUM(OTHER) AS OTHER,
                    sum(IF (DIFFERENCE < 0, NOTX - DIFFERENCE, NOTX)) AS NOTX
                FROM
                    `diarrhoea_case_tx_aggreagate` 
                WHERE
                    county = '{$request->county}' 
                GROUP BY
                    FACILITY_TYPE,
                    assessment";

        $data = \DB::select($sql);
        $cleanedData = [];

        foreach ($data as $d) {
            $cleanedData[$d->assessment][] = $d;
        }

        return $cleanedData;
    }

    function getFacilityDiarrhoeaClassification(Request $request){
        $sql = "SELECT
                    assessment,
                    facility_name,
                    SUM(classified) AS TOTAL_CLASSIFIED,
                    SUM( NO_CLASS_CASES ) AS NO_CLASS_CASES,
                    SUM( classified + ( IF ( DIFFERENCE > 0, NO_CLASS_CASES + DIFFERENCE, NO_CLASS_CASES ) ) ) AS TOTAL_CASES_AFTER_DIFF,
                    SUM( IF ( DIFFERENCE < 0, NOTX + abs( DIFFERENCE ), NOTX ) ) AS NOTX_AFTER_DIFF 
                FROM
                    `diarrhoea_case_tx_aggreagate` 
                WHERE
                    sub_county = '{$request->subcounty}'
                GROUP BY
                    facility_name,
                    assessment ";

        // die($sql);

        $data = \DB::select($sql);

        return $data;
    }

    function getFacilityDiarrhoeaTreatment(Request $request){
        $sql = "SELECT
                    facility_name,
                    assessment,
                    SUM( ANTIBIOTICS ) AS ANTIBIOTICS,
                    SUM(IV) AS IV,
                    SUM(COP) AS COP,
                    SUM(ZINC) AS ZINC,
                    SUM(ORS) AS ORS,
                    SUM(OTHER) AS OTHER,
                    sum(IF (DIFFERENCE < 0, NOTX - DIFFERENCE, NOTX)) AS NOTX
                FROM
                    `diarrhoea_case_tx_aggreagate` 
                WHERE
                    sub_county = '{$request->subcounty}' 
                GROUP BY
                    facility_name,
                    assessment";

        $data = \DB::select($sql);
        $cleanedData = [];

        foreach ($data as $d) {
            $cleanedData[$d->assessment][] = $d;
        }

        return $cleanedData;
    }

    function getDiarrhoeaSubcountyLocClassification(Request $request){
        $sql = "SELECT
                    assessment,
                    FACILITY_TYPE,
                    SUM(classified) AS TOTAL_CLASSIFIED,
                    SUM( NO_CLASS_CASES ) AS NO_CLASS_CASES,
                    SUM( classified + ( IF ( DIFFERENCE > 0, NO_CLASS_CASES + DIFFERENCE, NO_CLASS_CASES ) ) ) AS TOTAL_CASES_AFTER_DIFF,
                    SUM( IF ( DIFFERENCE < 0, NOTX + abs( DIFFERENCE ), NOTX ) ) AS NOTX_AFTER_DIFF 
                FROM
                    `diarrhoea_case_tx_aggreagate` 
                WHERE
                    sub_county = '{$request->subcounty}'
                GROUP BY
                    FACILITY_TYPE,
                    assessment ";

        // die($sql);

        $data = \DB::select($sql);

        return $data;
    }

    function getDiarrhoeaLocTreatmentData(Request $request){
         $sql = "SELECT
                    FACILITY_TYPE,
                    assessment,
                    SUM( ANTIBIOTICS ) AS ANTIBIOTICS,
                    SUM(IV) AS IV,
                    SUM(COP) AS COP,
                    SUM(ZINC) AS ZINC,
                    SUM(ORS) AS ORS,
                    SUM(OTHER) AS OTHER,
                    sum(IF (DIFFERENCE < 0, NOTX - DIFFERENCE, NOTX)) AS NOTX
                FROM
                    `diarrhoea_case_tx_aggreagate` 
                WHERE
                    sub_county = '{$request->subcounty}' 
                GROUP BY
                    FACILITY_TYPE,
                    assessment";

        $data = \DB::select($sql);
        $cleanedData = [];

        foreach ($data as $d) {
            $cleanedData[$d->assessment][] = $d;
        }

        return $cleanedData;
    }
}
