<?php

namespace App\Http\Controllers\API;

use App\Imports\FacilityDataImport;
use App\PneumoniaCalculatedValue;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use function foo\func;

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

        $facility->COUNTY = (\App\County::find($request->county['value'])->first())->county;
        $facility->COUNTY_ID = $request->county['value'];
        $facility->SUB_COUNTY = $request->sub_county['label'];
        $facility->SUB_COUNTY_ID = $request->sub_county['value'];
        $facility->FACILITY_NAME = $request->facility_name;
        $facility->FACILITY_TYPE = $request->facility_type;
        $facility->STATUS = $request->status;

        $facility->save();

        return $facility;
    }

    function update(Request $request){
        $request->validate([
            'facility_name' =>  'required',
            'county'        =>  'required',
            'sub_county'    =>  'required',
            'facility_type' =>  'required',
            'status'        =>  'required'
        ]);

        $facility = \App\Facility::where('SURVEY_CTO_ID', $request->survey_cto_id)->firstOrFail();

        $facility->COUNTY = $request->county['label'];
        $facility->COUNTY_ID = $request->county['value'];
        $facility->SUB_COUNTY = $request->sub_county['label'];
        $facility->SUB_COUNTY_ID = $request->sub_county['value'];
        $facility->FACILITY_NAME = $request->facility_name;
        $facility->FACILITY_TYPE = $request->facility_type;
        $facility->STATUS = ($request->status) ? "1" : "0";

        $facility->save();

        return $facility;
    }

    function get($id){
        $facility = \App\Facility::where('SURVEY_CTO_ID', $id)->firstOrFail();

        return $facility;
    }

    function updateFacilityStatus(Request $request){
        $id = $request->id;

        $facility = \App\Facility::where('SURVEY_CTO_ID', $id)->first();

        $facility->STATUS = ($facility->STATUS == "1") ? "0" : "1";

        $facility->save();

        return $facility;
    }

    function uploadListing(Request $request){
        $request->validate([
            'file'  =>  'mimes:csv,xls,xlsx'
        ]);

        $path = $request->file->store('data/facilities');

        Excel::import(new FacilityDataImport(), $path);
    }

    function getTemporaryFacilities(){
        return \App\FacilityUploadTmp::all();
    }

    function deleteTemporaryFacility($id){
        $facility = \App\FacilityUploadTmp::findOrFail($id);

        $facility->delete();

        return ['message'   =>  "Successfully deleted {$facility->FACILITY_NAME} from temporary listing"];
    }

    function removeDuplicateTemporaryFacilities(Request $request){
        $duplicates = (collect($request->duplicates))->pluck('id')->toArray();

        \App\FacilityUploadTmp::whereIn('id', $duplicates)->delete();

        return ['message'   =>  'Successfully removed all duplicates from listing'];
    }

    function moveTemporaryToFacilityListing(Request $request){
        if (\App\FacilityUploadTmp::count() > 0) {
            $facilities = collect($request->facilities)->map(function ($facility) {
                return [
                    'COUNTY_ID' => $facility['COUNTY_ID'],
                    'COUNTY' => $facility['COUNTY'],
                    'SUB_COUNTY_ID' => $facility['SUB_COUNTY_ID'],
                    'SUB_COUNTY' => $facility['SUB_COUNTY'],
                    'FACILITY_NAME' => $facility['FACILITY_NAME'],
                    'FACILITY_TYPE' => $facility['FACILITY_TYPE'],
                    'STATUS' => $facility['STATUS']
                ];
            })->toArray();

            $insertedFacilities = \App\Facility::insert($facilities);
            \App\FacilityUploadTmp::truncate();

            return ['message'   =>  'Successfully added items to master facility listing'];
        }
    }

    function emptyTemporaryFacilityListing(Request $request){
        \App\FacilityUploadTmp::truncate();

        return ['message'   =>  'Successfully truncated table'];
    }
}
