<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSupervisionDataLegaciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('supervision_data_legacy', function (Blueprint $table) {
            $table->increments('id');
            $table->string('county');
            $table->string('sub_county');
            $table->string('facility_name');
            $table->string('facility_code');
            $table->string('sum_tx_ors_zn')->nullable();
            $table->string('sum_tx_ors')->nullable();
            $table->string('sum_tx_zn')->nullable();
            $table->string('sum_tx_antibiotics')->nullable();
            $table->string('sum_other')->nullable();
            $table->string('sum_tx_none')->nullable();
            $table->string('sum_no_dehydration')->nullable();
            $table->string('sum_some_dehydration')->nullable();
            $table->string('sum_sev_dehydration')->nullable();
            $table->string('sum_shock')->nullable();
            $table->string('sum_dysentry')->nullable();
            $table->string('sum_no_class')->nullable();
            $table->string('sum_tx_amox')->nullable();
            $table->string('sum_tx_cotri')->nullable();
            $table->string('sum_tx_benz')->nullable();
            $table->string('sum_tx_genta')->nullable();
            $table->string('sum_tx_chlor')->nullable();
            $table->string('sum_tx_pn_other')->nullable();
            $table->string('sum_tx_pn_none')->nullable();
            $table->string('sum_no_pneu')->nullable();
            $table->string('sum_pneu')->nullable();
            $table->string('sum_sev_pneu')->nullable();
            $table->string('sum_very_sev_dis')->nullable();
            $table->string('sum_no_pn_class')->nullable();
            $table->string('period');
            $table->integer('upload_id');
            $table->integer('assessment_type_id');

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
        Schema::dropIfExists('supervision_data_legacy');
    }
}
