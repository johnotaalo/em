<?php

use Illuminate\Database\Seeder;

use \App\Subcounty;

class FillCountyIDs extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$subcounties = Subcounty::all(); 

    	foreach ($subcounties as $subcounty) {
    		$cto_id = $subcounty->cto_id;
    		if($cto_id){
    			$numberLength = strlen((string) $cto_id);
    			$county_id = 0;

    			if ($numberLength == 4) {
    				$county_id = intval(substr($cto_id, 0, 1));
    			}else if ($numberLength == 5) {
    				$county_id = intval(substr($cto_id, 0, 2));
    			}
    			

    			$subcounty->COUNTY_ID = $county_id;

    			$subcounty->save();
    		}
    	} 
    }
}
