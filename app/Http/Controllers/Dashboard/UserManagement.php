<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserManagement extends Controller
{
    public function index(){
    	return view('dashboard.users.index');
    }

    public function add(Request $request){
    	return view('dashboard.users.add');
    }

    public function edit(Request $request){
    	return view('dashboard.users.edit')->with(['id' => $request->id]);
    }
}
