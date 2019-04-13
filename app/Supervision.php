<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Supervision extends Model
{
    protected $table = "supervision_data";

    protected $appends = ["assessment_type"];

    public function getAssessmentTypeAttribute(){
    	$agency = \App\AssessmentType::find($this->assessment_type_id);
        return $agency;
    }

    public function facility(){
    	return $this->belongsTo('\App\Facility', 'fname', 'SURVEY_CTO_ID');
    }
}
