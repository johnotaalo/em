<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStepToSupervisionData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('supervision_data', function (Blueprint $table) {
            $table->integer("step")->nullable();
        });

        Schema::table('pneumonia_calculated_values', function(Blueprint $table){
            $table->string("assessment_type_step")->nullable();
        });

        Schema::table('diarrhoea_calculated_values', function(Blueprint $table){
            $table->string("assessment_type_step")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('supervision_data', function (Blueprint $table) {
            $table->dropColumn(['step']);
        });

        Schema::table('pneumonia_calculated_values', function (Blueprint $table) {
            $table->dropColumn(['assessment_type_step']);
        });

        Schema::table('diarrhoea_calculated_values', function (Blueprint $table) {
            $table->dropColumn(['assessment_type_step']);
        });
    }
}
