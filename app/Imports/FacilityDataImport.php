<?php

namespace App\Imports;

use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\FacilityUploadTmp;

class FacilityDataImport implements ToModel, WithHeadingRow
{

    public function model(array $row)
    {
        return new FacilityUploadTmp(
            [
                'COUNTY_ID'     =>  $row['county_id'],
                'COUNTY'        =>  $row['county'],
                'SUB_COUNTY_ID' =>  $row['sub_county_id'],
                'SUB_COUNTY'    =>  $row['sub_county'],
                'FACILITY_NAME' =>  $row['facility_name'],
                'FACILITY_TYPE' =>  $row['facility_type'],
                'STATUS'        =>  ($row['status']) ? $row['status'] : 0
            ]
        );
    }
}
