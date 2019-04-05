<?php

namespace App\Http\Controllers\API;

ini_set('max_execution_time', -1);

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\SupervisionDataUploadTmp as SPUploadTmp;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\SupervisionDataImport;

use App\SupervisionUpload as Upload;

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

    function getCounties(){
        $counties = \App\County::orderBy('county', 'ASC')->get();

        return $counties;
    }


    function getAssessmentTypes(){
        return \App\AssessmentType::all();
    }

    function getPneumoniaData(){
    	$data = \DB::table('supervision_data')->select(\DB::raw("SUM(IFNULL(sev_amoxdt, 0) + IFNULL(pn_amox, 0) + IFNULL(noc_amox, 0) + IFNULL(noclass_amox, 0)) AS `AMOXDT`, SUM(IFNULL(sev_amoxsy, 0) + IFNULL(pn_amoxsy, 0) + IFNULL(noc_amoxsy, 0) + IFNULL(noclass_amoxsy, 0)) AS `AMOX_SYRUP`, SUM(IFNULL(sev_ctx, 0) + IFNULL(pn_ctx, 0) + IFNULL(noc_ctx, 0) + IFNULL(noclass_ctx, 0)) AS `CTX`, SUM(IFNULL(sev_other, 0) + IFNULL(pn_other, 0) + IFNULL(noc_other, 0) + IFNULL(noclass_other, 0) + IFNULL(sev_oxygen, 0) + IFNULL(pn_oxygen, 0) + IFNULL(noc_oxygen, 0) + IFNULL(noclass_oxygen, 0) + IFNULL(sev_gent, 0) + IFNULL(pn_gent, 0) + IFNULL(noc_gent, 0) + IFNULL(noclass_gent, 0) + IFNULL(sev_benz, 0) + IFNULL(pn_benz, 0) + IFNULL(noc_benz, 0) + IFNULL(noclass_benz, 0)) AS `INJECTABLES`"))->first();

    	$reponse = [];

    	foreach ($data as $key => $value) {
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
    	$data = \DB::table('supervision_data')->count('facility_name');

    	return $data;
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
        $upload = Upload::create([ "counties" => $counties, "path" => $path ]);

        $period = $request->input('duration')['month'] . " " . $request->input('duration')['year'];

        Excel::import(new SupervisionDataImport($upload->id, $request->input('assessmentType'), $period), $path);
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

    function uploadData(Request $request){

        $tempData = SPUploadTmp::exclude(['created_at', 'updated_at', 'upload_id']);
        if($request->warning == "true"){
            $tempData = $tempData->limit(500);
        }

        $tempIDs = $tempData->pluck("id");
        $tempData = $tempData->get()->toArray();
        // dd($tempIDs);

        foreach($tempData as $key => $value) {
            unset($tempData[$key]['id']);
        }

        // dd($tempData);

        \DB::table('supervision_data')->insert($tempData);

        if($request->warning == "true"){
            SPUploadTmp::destroy($tempIDs);
        }else{
            SPUploadTmp::query()->truncate();
        }

        return ['status' => 'success'];
    }

    function getCountyCoverage(){
        $counties = \DB::table('supervision_data')->distinct('county')->pluck('county');

        return $counties;
    }
}
