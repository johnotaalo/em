<?php

use Illuminate\Database\Seeder;

use Maatwebsite\Excel\Facades\Excel;
use App\Imports\AssessmentBootstrap;
use App\Supervision;

class UpdateAssessmentSteps extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = (new AssessmentBootstrap)->toArray(public_path("data/assessment_bootstrap.xlsx"));

        foreach ($data[0] as $row) {
        	$supervision = Supervision::where("county", $row['county'])->where("period", $row['period'])->where("assessment_type_id", $row["assessment_type_id"])->update(["step" => $row["step"]]);
        }

    	die("Foreach complete");
    }
}
