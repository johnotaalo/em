<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DataController extends Controller
{
    function uploadPage(){
       	return view('dashboard.data.uploadpage');
    }

    function countyPage(){
    	return view('dashboard.data.uploadpage');
    }
}
