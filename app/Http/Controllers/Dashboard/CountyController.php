<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CountyController extends Controller
{
    function index(){
    	return view('dashboard.county.index');
    }

    function breakdown(Request $request){
    	if (!$request->county) {
    		$county = \App\County::where('cto_id', '!=', null)->inRandomOrder()->first();
    		$request->county = $county->county;
    	}
    	return view('dashboard.county.breakdown')->with(['county' => $request->county]);
    }
}
