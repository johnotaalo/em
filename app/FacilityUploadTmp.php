<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FacilityUploadTmp extends Model
{
    protected $fillable = ["COUNTY", "COUNTY_ID", "SUB_COUNTY", "SUB_COUNTY_ID", "FACILITY_NAME", "FACILITY_TYPE", "STATUS"];

    protected $appends = ["duplicate"];

    public function getDuplicateAttribute(){
        $facility = \App\Facility::where('COUNTY', $this->COUNTY)->where('SUB_COUNTY', $this->SUB_COUNTY)->where('FACILITY_NAME', 'LIKE', $this->FACILITY_NAME)->count();

        return $facility > 0;
    }
}
