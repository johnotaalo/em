<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FacilityController extends Controller
{
    function search(Request $request){
    	$searchQueries = $request->get('normalSearch');
        $activeFilter = $request->get('activeStaffSearch');
        $limit = $request->get('limit');
        $page = $request->get('page');
        $ascending = $request->get('ascending');
        $byColumn = $request->get('byColumn');
        $orderBy = $request->get('orderBy');

        $queryBuilder = \App\Facility::select("COUNTY", "SUB_COUNTY", "FACILITY_NAME", "SURVEY_CTO_ID", 'FACILITY_TYPE', "STATUS");

        $columns = [
        	'SURVEY_CTO_ID',
        	'FACILITY_NAME',
        	'COUNTY',
        	'SUB_COUNTY',
        	'FACILITY_TYPE',
        	'STATUS'
        ];

        if($searchQueries){
        	$queryBuilder->where('COUNTY', 'LIKE', "%{$searchQueries}%");
            $queryBuilder->orWhere('SUB_COUNTY', 'LIKE', "%{$searchQueries}%");
            $queryBuilder->orWhere('FACILITY_NAME', 'LIKE', "%{$searchQueries}%");
            $queryBuilder->orWhere('SURVEY_CTO_ID', 'LIKE', "%{$searchQueries}%");
            $queryBuilder->orWhere('COUNTY_ID', 'LIKE', "%{$searchQueries}%");
            $queryBuilder->orWhere('SUB_COUNTY_ID', 'LIKE', "%{$searchQueries}%");
        }

        if ($orderBy) {
        	$queryBuilder->orderBy($orderBy, ($ascending) ? 'ASC' : 'DESC');
        }

        $count = $queryBuilder->count();

        $queryBuilder = $queryBuilder->limit($limit)->skip($limit * ($page - 1));
        $data = $queryBuilder->get();

        return [
    		'data' 	=>	$data,
    		'count'	=>	$count
    	];
    }

    function getFacilityTypes(){
        $facilityTypes = \App\FacilityType::all();

        return $facilityTypes;
    }

    function store(Request $request){
        $request->validate([
            'facility_name' =>  'required|unique:facilities,FACILITY_NAME',
            'county'        =>  'required',
            'sub_county'    =>  'required',
            'facility_type' =>  'required',
            'status'        =>  'required'
        ]);

        $facility = new \App\Facility();

        $facility->COUNTY = $request->county['label'];
        $facility->COUNTY_ID = $request->county['value'];
        $facility->SUB_COUNTY = $request->sub_county['label'];
        $facility->SUB_COUNTY_ID = $request->sub_county['value'];
        $facility->FACILITY_NAME = $request->facility_name;
        $facility->FACILITY_TYPE = $request->facility_type;
        $facility->STATUS = $request->status;

        $facility->save();

        return $facility;
    }
}
