<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FacilityController extends Controller
{
    function facilitiesPage(){
      return view('dashboard.data.facilities');
    }

    function add(){
    	return view('dashboard.data.addFacilities');
    }

    function edit($id){
        $data = ['id'   =>  $id];
        return view('dashboard.data.editFacility')->with($data);
    }

    function temporaryFacilityList(){
        return view('dashboard.data.temporaryFacilities');
    }
}
