<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserManagement extends Controller
{
    public function index(Request $request){
    	$searchQueries = $request->get('query');
        $limit = $request->get('limit');
        $page = $request->get('page');
        // $ascending = 0;
        $ascending = $request->get('ascending');
        $byColumn = $request->get('byColumn');
        $orderBy = $request->get('orderBy');

        $queryBuilder = \App\User::select('id', 'name', 'email', 'created_at', 'updated_at', 'user_type', 'status');
        if ($searchQueries) {
        	$queryBuilder = $queryBuilder->where('name', 'LIKE', "%{$searchQueries}%");
        	$queryBuilder = $queryBuilder->orWhere('email', 'LIKE', "%{$searchQueries}%");
        }

        $count = $queryBuilder->count();
        $queryBuilder->limit($limit)->skip($limit * ($page - 1));

        $records = $queryBuilder->get();

        return [
        	'data' => $records,
            'count' => $count
        ];
    }

    public function add(Request $request){
    	$user = new User();

    	return $request->input();
    }

    public function validateEmail(Request $request){
    	return ['exists' => \App\User::where('email', $request->email)->exists()];
    }
}
