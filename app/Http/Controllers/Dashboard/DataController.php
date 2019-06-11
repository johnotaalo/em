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
}
