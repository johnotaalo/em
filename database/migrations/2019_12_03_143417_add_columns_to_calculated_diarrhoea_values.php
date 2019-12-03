<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToCalculatedDiarrhoeaValues extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('diarrhoea_calculated_values', function (Blueprint $table) {
            $table->integer('cname');
            $table->integer('scname');
            $table->integer('fname');
            $table->string('assessment');
            $table->string('assessment_type');
            $table->integer('NO_CLASS_CASES');
            $table->integer('classified');
            $table->integer('total_cases');
            $table->integer('ANTIBIOTICS');
            $table->integer('IV');
            $table->integer('NOTX');
            $table->integer('COP');
            $table->integer('ZINC');
            $table->integer('ORS');
            $table->integer('OTHER');
            $table->integer('TOTAL_TX');
            $table->integer('DIFFERENCE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('diarrhoea_calculated_values', function (Blueprint $table) {
            $table->dropColumn(['cname', 'scname', 'fname', 'assessment', 'assessment_type']);
        });
    }
}
