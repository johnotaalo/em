<?php

use Illuminate\Database\Seeder;

use App\FacilityType;

class FacilityTypes extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $facilityTypes = \App\Facility::select('FACILITY_TYPE')->distinct()->get();

        FacilityType::truncate();
        
        foreach ($facilityTypes as $type) {
        	if($type->FACILITY_TYPE){
	        	$facilityType = new FacilityType();

	        	$facilityType->facility_type = $type->FACILITY_TYPE;
	        	$facilityType->description = $type->FACILITY_TYPE;

	        	$facilityType->save();
	        }
        }
    }
}
