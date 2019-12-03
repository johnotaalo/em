<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    function index(){
    	return view('dashboard.home.index');
    }

    public function countryOverview(){
        return view('dashboard.home.country-overview');
    }

    public function countyOverview(){
        return view('dashboard.home.county-overview');
    }
}
