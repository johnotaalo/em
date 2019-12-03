<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DiarrhoeaCalculatedValue extends Model
{
    function county(){
    	return $this->belongsTo(\App\County::class, 'cname', 'cto_id');
    }

    function facility(){
    	return $this->belongsTo(\App\Facility::class, 'fname', 'SURVEY_CTO_ID');
    }

    function subcounty(){
    	return $this->belongsTo(\App\Subcounty::class, 'scname', 'cto_id');
    }
}
