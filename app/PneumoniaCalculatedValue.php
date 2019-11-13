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
}
