<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\SupervisionDataUploadTmp as SPUploadTmp;
use App\SupervisionUpload;
use App\SupervisionDataLegacyTmp;

class DataController extends Controller
{
    function uploadPage(){
    	$temporaryData = SPUploadTmp::all();
      $legacyTmpData = SupervisionDataLegacyTmp::count();
    	if(!count($temporaryData) && $legacyTmpData == 0){
       		return view('dashboard.data.uploadpage');
       	}else{
          $isLegacy = ($legacyTmpData > 0) ? true : false;
       		return view('dashboard.data.temporaryData')->with(['isLegacy' => $isLegacy]);
       	}
    }

    function cancelUpload(){
      $legacyTmpData = SupervisionDataLegacyTmp::count();
      if($legacyTmpData > 0){
        SupervisionDataLegacyTmp::query()->truncate();
      }else{
        SPUploadTmp::query()->truncate();
      }
  
      return redirect('/data/upload');
    }

    function countyPage(){
    	return view('dashboard.data.uploadpage');
    }

    function facilitiesPage(){
      return view('dashboard.data.facilities');
    }

    function pullCalculatedData(){

      $diarrhoeaData = \DB::select('SELECT * FROM diarrhoea_case_tx_aggreagate');
      $diarrhoeaInsertData = collect($diarrhoeaData)->map(function($data){
        $c = \App\County::where('county', $data->county)->first();
        if (!is_null($c)) {
          $county = $c->cto_id;
        }else{
          $county = $data->cname;
        }
        
        $sub_county = \App\Subcounty::where("subcounty_name", $data->sub_county)->first();
        $subcounty_id = ($sub_county) ? $sub_county->cto_id : 0;
        if($subcounty_id == 0){
          $subcounty_id = \App\Facility::where('SURVEY_CTO_ID', $data->fname)->first()->SUB_COUNTY_ID;
        }

        return [
          'cname'           =>  $county,
          'scname'          =>  $subcounty_id,
          'fname'           =>  (int)$data->fname,
          'assessment'      =>  $data->assessment,
          'assessment_type' =>  $data->assessment_type,
          'NO_CLASS_CASES'  =>  $data->NO_CLASS_CASES,
          'classified'      =>  $data->classified,
          'total_cases'     =>  $data->total_cases,
          'ANTIBIOTICS'     =>  $data->ANTIBIOTICS,
          'IV'              =>  $data->IV,
          'NOTX'            =>  $data->NOTX,
          'COP'             =>  $data->COP,
          'ZINC'            =>  $data->ZINC,
          'ORS'             =>  $data->ORS,
          'OTHER'           =>  $data->OTHER,
          'TOTAL_TX'        =>  $data->TOTAL_TX,
          'DIFFERENCE'      =>  $data->DIFFERENCE     
        ];
      })->toArray();

      $pneumoniaClassificationData = \DB::select("SELECT * FROM pneumonia_facility_classification c JOIN pneumonia_facility_treatment_data t ON t.id = c.id");
      $pneuClassInsertData = collect($pneumoniaClassificationData)->map(function($data){
        $county = \App\County::where('county', $data->county)->first()->cto_id;
        $sub_county = \App\Subcounty::where("subcounty_name", $data->sub_county)->first();
        // dd($sub_county);
        $subcounty_id = ($sub_county) ? $sub_county->cto_id : 0;
        if($subcounty_id == 0){
          $subcounty_id = \App\Facility::where('SURVEY_CTO_ID', $data->fname)->first()->SUB_COUNTY_ID;
        }
        return [
          'cname' => $county,
          'scname' => $subcounty_id,
          'fname' =>  (int)$data->fname,
          'assessment' => $data->assessment,
          'assessment_type' => $data->assessment_type, 
          'total_classified' => $data->TOTAL_CLASSIFIED,
          'total_tx' => $data->TOTAL_TX,
          'total_tx_notx_ex' => $data->TOTAL_TX_NOTX_EX,
          'no_classification' => $data->NO_CLASSIFICATION,
          'treatment_diff' => $data->TREATMENT_DIFF,
          'abs_treatment_diff' => $data->ABS_TREATMENT_DIFF,
          'no_clasification_incl_diff' => $data->NO_CLASSIFICATION_INCL_DIFF,
          'total_cases_after_dif' => $data->TOTAL_CASES_AFTER_DIF,
          'total_tx_after_dif' => $data->TOTAL_TX_AFTER_DIF,
          'AMOXDT'  =>  $data->AMOXDT,
          'AMOX_SYRUP'  =>  $data->AMOX_SYRUP,
          'OXYGEN'  =>  $data->OXYGEN,
          'CTX' =>  $data->CTX,
          'GENT'  =>  $data->GENT,
          'BENZ'  =>  $data->BENZ,
          'BENZ_GENT' =>  $data->BENZ_GENT,
          'INJECTABLES' =>  $data->INJECTABLES,
          'ANTIBIOTICS' =>  $data->ANTIBIOTICS,
          'OTHER' =>  $data->OTHER,
          'ANTI_OTHER'  =>  $data->ANTI_OTHER,
          'NOTX'  =>  $data->NOTX
        ];
      })->toArray();

      // dd($pneuClassInsertData);

      \App\PneumoniaCalculatedValue::query()->truncate();
      \App\DiarrhoeaCalculatedValue::query()->truncate();

      \App\PneumoniaCalculatedValue::insert($pneuClassInsertData);
      \App\DiarrhoeaCalculatedValue::insert($diarrhoeaInsertData);
      // dd($pneuClassInsertData);
    }
}
