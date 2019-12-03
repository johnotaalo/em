<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PneumoniaCalculatedValue extends Model
{
    protected $fillable = [
    	'cname',
		'scname',
		'fname',
		'assessment',
		'assessment_type',
		'total_classified',
		'total_tx',
		'total_tx_notx_ex',
		'no_classification',
		'treatment_diff',
		'abs_treatment_diff',
		'no_clasification_incl_diff',
		'total_cases_after_dif',
		'total_tx_after_dif'
    ];

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
