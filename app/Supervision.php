<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Supervision extends Model
{
    protected $table = "supervision_data";

    protected $appends = ["assessment_type", "assessment_cycle"];

    public function getAssessmentTypeAttribute(){
    	$agency = \App\AssessmentType::find($this->assessment_type_id);
        return $agency;
    }

    public function getAssessmentCycleAttribute(){
    	$step = $this->step;
    	$assessment_type = ($this->getAssessmentTypeAttribute())->assessment_type;

    	return ($this->assessment_type_id !== 3) ? $assessment_type : $assessment_type . " " . $step;
    }

    public function facility(){
    	return $this->belongsTo('\App\Facility', 'fname', 'SURVEY_CTO_ID');
    }
}
