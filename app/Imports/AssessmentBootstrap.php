<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\Importable;

class AssessmentBootstrap implements ToCollection, WithHeadingRow
{
	use Importable;
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
    	// $updateData = [];
     //    foreach ($collection as $row) {
    	// 	$udpateData[] = [
    	// 		'county'				=>	$row['county'],
    	// 		"period"				=>	$row['period'],
    	// 		"assessment_type_id"	=>	$row["assessment_type_id"],
    	// 		"step"					=> 	$row["step"]
    	// 	];
    	// }
    	// return $updateData;

    	return $collection;
    }
}
