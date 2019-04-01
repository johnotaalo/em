<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\SupervisionDataUploadTmp as SPUploadTmp;

class DataController extends Controller
{
    function uploadPage(){
    	$temporaryData = SPUploadTmp::all();
    	if(!count($temporaryData)){
       		return view('dashboard.data.uploadpage');
       	}else{
       		return view('dashboard.data.temporaryData');
       	}
    }

    function cancelUpload(){
      SPUploadTmp::query()->truncate();

      return redirect('/data/upload');
    }

    function countyPage(){
    	return view('dashboard.data.uploadpage');
    }
}
