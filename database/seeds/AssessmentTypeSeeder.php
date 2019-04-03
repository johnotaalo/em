<?php

use Illuminate\Database\Seeder;
use App\AssessmentType;

class AssessmentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $assessments = [
        	[
        		"assessment_type" => "Baseline"
        	],
        	[
        		"assessment_type" => "Mid-Term"
        	],
        	[
        		"assessment_type" => "Facility Supervision"
        	],
        	[
        		"assessment_type" => "End-Line Supervision"
        	]
        ];

        $now = \Carbon\Carbon::now();

        foreach ($assessments as &$assessment) {
        	$assessment['created_at'] = $now;
        	$assessment['updated_at'] = $now;
        }

        AssessmentType::insert($assessments);
    }
}
