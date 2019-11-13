<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePneumoniaCalculatedValuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pneumonia_calculated_values', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('cname');
            $table->integer('scname');
            $table->integer('fname');
            $table->string('assessment');
            $table->string('assessment_type');
            $table->integer('total_classified');
            $table->integer('total_tx');
            $table->integer('total_tx_notx_ex');
            $table->integer('no_classification');
            $table->integer('treatment_diff');
            $table->integer('abs_treatment_diff');
            $table->integer('no_clasification_incl_diff');
            $table->integer('total_cases_after_dif');
            $table->integer('total_tx_after_dif');
            $table->integer('AMOXDT');
            $table->integer('AMOX_SYRUP');
            $table->integer('OXYGEN');
            $table->integer('CTX');
            $table->integer('GENT');
            $table->integer('BENZ');
            $table->integer('BENZ_GENT');
            $table->integer('INJECTABLES');
            $table->integer('ANTIBIOTICS');
            $table->integer('OTHER');
            $table->integer('ANTI_OTHER');
            $table->integer('NOTX');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pneumonia_calculated_values');
    }
}
