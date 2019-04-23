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
    	return view('dashboard.county.breakdown')->with(['county' => $request->county]);
    }
}
