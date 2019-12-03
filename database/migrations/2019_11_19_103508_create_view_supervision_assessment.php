<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateViewSupervisionAssessment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement($this->dropView());
        \DB::statement($this->createView());
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('view_supervision_assessment');
    }

    private function createView() : string {
        return "CREATE VIEW `supervision_data_with_assessment` AS SELECT
                    d.*,
                    t.assessment_type,
                    (
                    CASE
                            
                            WHEN ( `d`.`assessment_type_id` = 3 ) THEN
                            concat( `t`.`assessment_type`, ' ', CONVERT ( substring_index( `d`.`period`, ' ',- ( 1 ) ) USING utf8mb4 ) ) ELSE `t`.`assessment_type` 
                        END 
                        ) AS `assessment`
                        
                    FROM
                    supervision_data d
                    JOIN assessment_types t ON t.id = d.assessment_type_id";
    }

    private function dropView() : string {
        return "DROP VIEW IF EXISTS `supervision_data_with_assessment`";
    }
}