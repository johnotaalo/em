<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Facility extends Model
{
    protected $primaryKey = "SURVEY_CTO_ID";
//    public $timestamps = false;

    protected $fillable = [
        'COUNTY_ID',
        'COUNTY',
        'SUB_COUNTY_ID',
        'SUB_COUNTY',
        'FACILITY_NAME',
        'FACILITY_TYPE',
        'STATUS'
    ];
}
